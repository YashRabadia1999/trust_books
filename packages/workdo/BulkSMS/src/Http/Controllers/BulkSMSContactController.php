<?php

namespace Workdo\BulkSMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Workdo\BulkSMS\DataTables\BulkSMSContactDatatable;
use Workdo\BulkSMS\Entities\BulksmsContact;
use Workdo\BulkSMS\Entities\SinglesmsSend;
use Workdo\BulkSMS\Events\CreateContact;
use Workdo\BulkSMS\Events\DestoryContact;
use Workdo\BulkSMS\Events\UpdateContact;

class BulkSMSContactController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(BulkSMSContactDatatable $dataTable)
    {
        if (Auth::user()->isAbleTo('bulksms_contact manage')) {
            return $dataTable->render('bulk-sms::contact.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('bulksms_contact create')) {
            return view('bulk-sms::contact.create');
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('bulksms_contact create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required',
                    'mobile_no' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    'zip' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $bulksmsContact = new BulksmsContact();
            $bulksmsContact->name = $request->name;
            $bulksmsContact->email = $request->email;
            $bulksmsContact->mobile_no = $request->mobile_no;
            $bulksmsContact->city = $request->city;
            $bulksmsContact->state = $request->state;
            $bulksmsContact->zip = $request->zip;
            $bulksmsContact->workspace = getActiveWorkSpace();
            $bulksmsContact->created_by = creatorId();
            $bulksmsContact->save();
            event(new CreateContact($request, $bulksmsContact));

            return redirect()->back()->with('success', __('The contact has been created successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('bulk-sms::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('bulksms_contact edit')) {
            $bulksmsContact = BulksmsContact::find($id);
            return view('bulk-sms::contact.edit', compact('bulksmsContact'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('bulksms_contact edit')) {
            $bulksmsContact = BulksmsContact::find($id);

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required',
                    'mobile_no' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    'zip' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $bulksmsContact->name = $request->name;
            $bulksmsContact->email = $request->email;
            $bulksmsContact->mobile_no = $request->mobile_no;
            $bulksmsContact->city = $request->city;
            $bulksmsContact->state = $request->state;
            $bulksmsContact->zip = $request->zip;
            $bulksmsContact->save();
            event(new UpdateContact($request, $bulksmsContact));

            return redirect()->back()->with('success', __('The contact details are updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('bulksms_contact delete')) {
            $bulksmsContact = BulksmsContact::find($id);
            $contact = SinglesmsSend::where('mobile_no', $bulksmsContact->mobile_no)->exists();
            if ($contact) {
                return redirect()->back()->with('error', __('Contact is in use and cannot be deleted.'));
            }
            event(new DestoryContact($bulksmsContact));
            $bulksmsContact->delete();

            return redirect()->back()->with('success', __('The contact has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function loadDataModal()
    {
        if (Auth::user()->isAbleTo('bulksms_contact create')) {
            return view('bulk-sms::contact.load_data');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function loadCustomersAndUsers(Request $request)
    {
        if (Auth::user()->isAbleTo('bulksms_contact create')) {
            $loadCustomers = $request->has('load_customers') && $request->load_customers == 'on';
            $loadUsers = $request->has('load_users') && $request->load_users == 'on';

            $imported = 0;
            $skipped = 0;

            // Load Customers
            if ($loadCustomers && module_is_active('Account')) {
                $customers = \Workdo\Account\Entities\Customer::where('created_by', creatorId())
                    ->where('workspace', getActiveWorkSpace())
                    ->get();

                foreach ($customers as $customer) {
                    if (!empty($customer->contact) && !empty($customer->name)) {
                        $exists = BulksmsContact::where('mobile_no', $customer->contact)
                            ->where('created_by', creatorId())
                            ->where('workspace', getActiveWorkSpace())
                            ->exists();

                        if (!$exists) {
                            BulksmsContact::create([
                                'name' => $customer->name,
                                'email' => $customer->email ?? '',
                                'mobile_no' => $customer->contact,
                                'city' => $customer->billing_city ?? '',
                                'state' => $customer->billing_state ?? '',
                                'zip' => $customer->billing_zip ?? '',
                                'workspace' => getActiveWorkSpace(),
                                'created_by' => creatorId(),
                            ]);
                            $imported++;
                        } else {
                            $skipped++;
                        }
                    }
                }
            }

            // Load Users
            if ($loadUsers) {
                $users = \App\Models\User::where('created_by', creatorId())
                    ->where('workspace_id', getActiveWorkSpace())
                    ->whereNotIn('type', ['super admin'])
                    ->get();

                foreach ($users as $user) {
                    if (!empty($user->mobile_no) && !empty($user->name)) {
                        $exists = BulksmsContact::where('mobile_no', $user->mobile_no)
                            ->where('created_by', creatorId())
                            ->where('workspace', getActiveWorkSpace())
                            ->exists();

                        if (!$exists) {
                            BulksmsContact::create([
                                'name' => $user->name,
                                'email' => $user->email ?? '',
                                'mobile_no' => $user->mobile_no,
                                'city' => '',
                                'state' => '',
                                'zip' => '',
                                'workspace' => getActiveWorkSpace(),
                                'created_by' => creatorId(),
                            ]);
                            $imported++;
                        } else {
                            $skipped++;
                        }
                    }
                }
            }

            $message = __('Data loaded successfully. Imported: :imported, Skipped (duplicates): :skipped', [
                'imported' => $imported,
                'skipped' => $skipped
            ]);

            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function fileImportExport()
    {
        if (Auth::user()->isAbleTo('bulksms_contact import')) {
            return view('bulk-sms::contact.import');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
    public function fileImportModal()
    {
        if (Auth::user()->isAbleTo('bulksms_contact import')) {
            return view('bulk-sms::contact.import_modal');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
    public function fileImport(Request $request)
    {
        if (Auth::user()->isAbleTo('bulksms_contact import')) {
            session_start();

            $error = '';
            $html = '';

            if ($request->file->getClientOriginalName() != '') {
                $file_array = explode(".", $request->file->getClientOriginalName());

                $extension = end($file_array);
                if ($extension == 'csv') {
                    $file_data = fopen($request->file->getRealPath(), 'r');

                    $file_header = fgetcsv($file_data);
                    $html .= '<table class="table table-bordered"><tr>';

                    for ($count = 0; $count < count($file_header); $count++) {
                        $html .= '
                                <th>
                                    <select name="set_column_data" class="form-control set_column_data" data-column_number="' . $count . '">
                                    <option value="">Set Count Data</option>
                                            <option value="name">Name</option>
                                            <option value="email">Email</option>
                                            <option value="mobile_no">Mobile No</option>
                                            <option value="city">City</option>
                                            <option value="state">State</option>
                                            <option value="zip">Zip Code</option>
                                    </select>
                                </th>
                                ';
                    }
                    $html .= '</tr>';
                    $limit = 0;
                    while (($row = fgetcsv($file_data)) !== false) {
                        $limit++;

                        $html .= '<tr>';

                        for ($count = 0; $count < count($row); $count++) {
                            $html .= '<td>' . $row[$count] . '</td>';
                        }

                        $html .= '</tr>';

                        $temp_data[] = $row;
                    }
                    $_SESSION['file_data'] = $temp_data;
                } else {
                    $error = 'Only <b>.csv</b> file allowed';
                }
            } else {

                $error = 'Please Select CSV File';
            }
            $output = array(
                'error' => $error,
                'output' => $html,
            );

            return json_encode($output);
        } else {
            return redirect()->back()->with('error', __('permission Denied'));
        }

    }
    public function contactImportdata(Request $request)
    {
        if (Auth::user()->isAbleTo('bulksms_contact import')) {
            session_start();
            $html = '<h3 class="text-danger text-center">Below data is not inserted</h3></br>';
            $flag = 0;
            $html .= '<table class="table table-bordered"><tr>';

            $file_data = $_SESSION['file_data'];
            unset($_SESSION['file_data']);
            foreach ($file_data as $row) {
                $bulksmsContact = BulksmsContact::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->where('mobile_no', 'like', $row[$request->mobile_no])->get();

                if ($bulksmsContact->isEmpty()) {

                    try {
                        BulksmsContact::create([
                            'name' => $row[$request->name],
                            'email' => $row[$request->email],
                            'mobile_no' => $row[$request->mobile_no],
                            'city' => $row[$request->city],
                            'state' => $row[$request->state],
                            'zip' => $row[$request->zip],
                            'created_by' => creatorId(),
                            'workspace' => getActiveWorkSpace(),
                        ]);
                    } catch (\Exception $e) {
                        $flag = 1;
                        $html .= '<tr>';

                        $html .= '<td>' . $row[$request->name] . '</td>';
                        $html .= '<td>' . $row[$request->email] . '</td>';
                        $html .= '<td>' . $row[$request->mobile_no] . '</td>';
                        $html .= '<td>' . $row[$request->city] . '</td>';
                        $html .= '<td>' . $row[$request->state] . '</td>';
                        $html .= '<td>' . $row[$request->zip] . '</td>';

                        $html .= '</tr>';
                    }
                } else {
                    $flag = 1;
                    $html .= '<tr>';

                    $html .= '<td>' . $row[$request->name] . '</td>';
                    $html .= '<td>' . $row[$request->email] . '</td>';
                    $html .= '<td>' . $row[$request->mobile_no] . '</td>';
                    $html .= '<td>' . $row[$request->city] . '</td>';
                    $html .= '<td>' . $row[$request->state] . '</td>';
                    $html .= '<td>' . $row[$request->zip] . '</td>';

                    $html .= '</tr>';
                }
            }

            $html .= '
                            </table>
                            <br />
                            ';
            if ($flag == 1) {

                return response()->json([
                    'html' => true,
                    'response' => $html,
                ]);
            } else {
                return response()->json([
                    'html' => false,
                    'response' => 'Data Imported Successfully',
                ]);
            }

        } else {
            return response()->json([
                'html' => false,
                'response' => 'Permission denied.',
            ]);
        }
    }
}
