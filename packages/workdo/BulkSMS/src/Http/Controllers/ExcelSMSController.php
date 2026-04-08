<?php

namespace Workdo\BulkSMS\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Workdo\BulkSMS\Entities\CustomerMessage;
use Workdo\BulkSMS\Entities\SendMsg;
use Workdo\SmsCredit\Helpers\SmsCreditHelper;

class ExcelSMSController extends Controller
{
    /**
     * Show the form for uploading Excel file
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('bulksms_contact create')) {
            $settings = getCompanyAllSetting();
            $senderIds = [];

            if (!empty($settings['bulksms_sender_ids'])) {
                $senderIds = array_map('trim', explode(',', $settings['bulksms_sender_ids']));
            } elseif (!empty($settings['sender_ids'])) {
                $senderIds = array_map('trim', explode(',', $settings['sender_ids']));
            }

            if (empty($senderIds)) {
                $senderIds = ['DEFAULT'];
            }

            // Get all custom message templates
            $customMessages = CustomerMessage::where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->get(['id', 'name', 'message']);

            return view('bulk-sms::excelsms.create', compact('senderIds', 'customMessages'));
        }

        return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * Process and send SMS from uploaded Excel file
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('bulksms_contact create')) {
            $validator = Validator::make($request->all(), [
                'excel_file' => 'required|file|mimes:xlsx,xls,csv',
                'sender_id' => 'required|string',
                'message_type' => 'required|in:excel,custom',
                'custom_message_id' => 'required_if:message_type,custom',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            try {
                $file = $request->file('excel_file');
                $spreadsheet = IOFactory::load($file->getRealPath());
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();

                // Remove header row if exists
                if (count($rows) > 0) {
                    $firstRow = $rows[0];
                    // Check if first row looks like a header
                    if (is_string($firstRow[0]) && (strtolower($firstRow[0]) === 'name' || strtolower($firstRow[0]) === 'recipient')) {
                        array_shift($rows);
                    }
                }

                if (empty($rows)) {
                    return redirect()->back()->with('error', __('Excel file is empty or has no data rows.'));
                }

                // Get custom message if selected
                $customMessage = null;
                if ($request->message_type === 'custom' && $request->custom_message_id) {
                    $customMessage = CustomerMessage::find($request->custom_message_id);
                    if (!$customMessage) {
                        return redirect()->back()->with('error', __('Selected message template not found.'));
                    }
                }

                $settings = getCompanyAllSetting();
                $username = $settings['bulksms_username'] ?? '';
                $password = $settings['bulksms_password'] ?? '';

                if (empty($username) || empty($password)) {
                    return redirect()->back()->with('error', __('BulkSMS credentials not configured. Please check settings.'));
                }

                $successCount = 0;
                $failedCount = 0;
                $errors = [];
                $totalCreditsUsed = 0;

                foreach ($rows as $index => $row) {
                    $rowNumber = $index + 2; // +2 because index starts at 0 and we may have removed header

                    // Skip empty rows
                    if (empty($row[0]) && empty($row[1])) {
                        continue;
                    }

                    $name = $row[0] ?? '';
                    $phone = $row[1] ?? '';

                    // Use custom message or message from Excel column 3
                    if ($customMessage) {
                        $message = $customMessage->message;
                    } else {
                        $message = $row[2] ?? '';
                    }

                    // Validate required fields
                    if (empty($phone)) {
                        $errors[] = "Row {$rowNumber}: Phone number is required";
                        $failedCount++;
                        continue;
                    }

                    if (empty($message)) {
                        $errors[] = "Row {$rowNumber}: Message is required";
                        $failedCount++;
                        continue;
                    }

                    // Clean phone number
                    $phone = preg_replace('/[^0-9]/', '', $phone);

                    if (empty($phone)) {
                        $errors[] = "Row {$rowNumber}: Invalid phone number format";
                        $failedCount++;
                        continue;
                    }

                    // Send SMS
                    try {
                        // Calculate credits for this message (first 150 chars = 1, then 100 chars = 1 each)
                        $messageLength = strlen($message);
                        $creditsNeeded = SmsCreditHelper::calculateCreditsNeeded($messageLength);

                        // Check credits before sending (only if SmsCredit module is active)
                        if (module_is_active('SmsCredit')) {
                            if (!SmsCreditHelper::hasCredits($creditsNeeded)) {
                                $errors[] = "Row {$rowNumber} ({$name} - {$phone}): Insufficient credits (needs {$creditsNeeded})";
                                $failedCount++;
                                continue;
                            }
                        }

                        $result = SendMsg::sendSms($phone, $message, $request->sender_id);

                        if ($result['status']) {
                            $successCount++;
                            // Deduct credits for successful send (only if SmsCredit module is active)
                            if (module_is_active('SmsCredit')) {
                                SmsCreditHelper::useCredits(
                                    $creditsNeeded,
                                    "Excel SMS to {$phone} ({$messageLength} chars)"
                                );
                                $totalCreditsUsed += $creditsNeeded;
                            }
                        } else {
                            $failedCount++;
                            $errorMsg = $result['message'] ?? 'Unknown error';
                            $errors[] = "Row {$rowNumber} ({$name} - {$phone}): {$errorMsg}";
                        }
                    } catch (\Exception $e) {
                        $failedCount++;
                        $errors[] = "Row {$rowNumber} ({$name} - {$phone}): " . $e->getMessage();
                    }                    // Add small delay to avoid rate limiting
                    usleep(100000); // 0.1 second delay
                }

                $message = "SMS sending completed. Success: {$successCount}, Failed: {$failedCount}";
                if (module_is_active('SmsCredit') && $totalCreditsUsed > 0) {
                    $message .= ". Credits used: {$totalCreditsUsed}";
                }

                if (!empty($errors)) {
                    $message .= "\n\nErrors:\n" . implode("\n", array_slice($errors, 0, 10));
                    if (count($errors) > 10) {
                        $message .= "\n... and " . (count($errors) - 10) . " more errors.";
                    }
                }

                if ($successCount > 0) {
                    return redirect()->back()->with('success', $message);
                } else {
                    return redirect()->back()->with('error', $message);
                }

            } catch (\Exception $e) {
                return redirect()->back()->with('error', __('Error processing Excel file: ') . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * Download sample Excel template
     */
    public function downloadSample()
    {
        if (Auth::user()->isAbleTo('bulksms_contact create')) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set headers
            $sheet->setCellValue('A1', 'Name');
            $sheet->setCellValue('B1', 'Phone Number');
            $sheet->setCellValue('C1', 'Message');

            // Add sample data
            $sheet->setCellValue('A2', 'John Doe');
            $sheet->setCellValue('B2', '0501234567');
            $sheet->setCellValue('C2', 'Hello John, this is a test message.');

            $sheet->setCellValue('A3', 'Jane Smith');
            $sheet->setCellValue('B3', '0559876543');
            $sheet->setCellValue('C3', 'Hi Jane, welcome to our service!');

            // Style the header row
            $sheet->getStyle('A1:C1')->getFont()->setBold(true);
            $sheet->getStyle('A1:C1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFE0E0E0');

            // Auto-size columns
            foreach (range('A', 'C') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Create writer and download
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $fileName = 'sms_template_' . date('Y-m-d_His') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        }

        return redirect()->back()->with('error', __('Permission denied.'));
    }
}
