<?php

namespace Workdo\SmsCredit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Workdo\SmsCredit\DataTables\SmsCreditPurchaseDatatable;
use Workdo\SmsCredit\Entities\SmsCreditPurchase;
use Workdo\SmsCredit\Entities\SmsCreditBalance;
use Workdo\SmsCredit\Services\HubtelPaymentService;

class SmsCreditController extends Controller
{
    protected $ratePerSms = 0.07;
    protected $minAmount = 10;

    public function __construct()
    {
        $this->ratePerSms = env('SMS_RATE_PER_CREDIT', 0.07);
        $this->minAmount = env('SMS_MIN_PURCHASE_AMOUNT', 10);
    }

    /**
     * Display purchase history
     */
    public function index(SmsCreditPurchaseDatatable $dataTable)
    {
        // dd(Auth::user()->type);
        // dd('here');
        // if (Auth::check() && Auth::user()->isAbleTo('sms_credit manage')) {

        if (Auth::user()->type == 'super admin' || Auth::user()->type == 'company') {
            $creatorId = Auth::user()->id;
        } else {
            $creatorId = Auth::user()->created_by;
        }
        // dd($creatorId);
        $balance = SmsCreditBalance::getBalance($creatorId, getActiveWorkSpace());
        return $dataTable->render('sms-credit::index', compact('balance'));
        // }

        // return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * Show purchase form
     */
    public function create()
    {
        // if (Auth::check() && Auth::user()->isAbleTo('sms_credit create')) {
        $balance = SmsCreditBalance::getBalance(creatorId(), getActiveWorkSpace());
        $ratePerSms = $this->ratePerSms;
        $minAmount = $this->minAmount;

        return view('sms-credit::create', compact('balance', 'ratePerSms', 'minAmount'));
        // }

        // return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * Calculate credits based on amount
     */
    public function calculateCredits(Request $request)
    {
        $amount = $request->input('amount', 0);

        if ($amount < $this->minAmount) {
            return response()->json([
                'status' => false,
                'message' => "Minimum amount is GHS {$this->minAmount}"
            ]);
        }

        $credits = floor($amount / $this->ratePerSms);

        return response()->json([
            'status' => true,
            'credits' => $credits,
            'amount' => $amount,
            'rate' => $this->ratePerSms
        ]);
    }

    /**
     * Process purchase
     */
    public function store(Request $request)
    {
        // if (Auth::check() && Auth::user()->isAbleTo('sms_credit create')) {
        $validator = Validator::make($request->all(), [
            'amount' => "required|numeric|min:{$this->minAmount}",
            'mobile_number' => 'required|regex:/^[0-9]{10,15}$/',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $amount = $request->amount;
        $credits = floor($amount / $this->ratePerSms);

        // Create purchase record
        $purchase = new SmsCreditPurchase();
        $purchase->client_id = creatorId();
        $purchase->workspace = getActiveWorkSpace();
        $purchase->created_by = Auth::id();
        $purchase->credits_purchased = $credits;
        $purchase->amount_paid = $amount;
        $purchase->payment_method = 'hubtel';
        $purchase->mobile_number = $request->mobile_number;
        $purchase->status = 'pending';
        $purchase->save();

        // Initialize Hubtel payment
        $hubtel = new HubtelPaymentService();
        $paymentData = [
            'reference' => 'SMSCREDIT_' . $purchase->id . '_' . time(),
            'description' => "Purchase {$credits} SMS Credits",
            'customer_name' => Auth::user()->name,
            'mobile_number' => $request->mobile_number,
            'email' => Auth::user()->email,
            'amount' => $amount,
            'callback_url' => route('sms-credit.payment.callback'),
            'cancel_url' => route('sms-credit.index'),
            'return_url' => route('sms-credit.payment.success'),
        ];

        $result = $hubtel->initiatePayment($paymentData);

        if ($result['status']) {
            $purchase->transaction_id = $result['data']['transactionId'] ?? null;
            $purchase->payment_response = json_encode($result['data']);
            $purchase->save();

            // Check if payment requires user action (e.g., USSD prompt)
            if (isset($result['data']['checkoutUrl'])) {
                return redirect($result['data']['checkoutUrl']);
            }

            return redirect()->route('sms-credit.show', $purchase->id)
                ->with('success', __('Payment initiated. Please check your phone to complete the transaction.'));
        }

        $purchase->status = 'failed';
        $purchase->payment_response = json_encode($result);
        $purchase->save();

        $errorMsg = $result['message'] ?? __('Payment initiation failed.');
        if (isset($result['error'])) {
            $errorMsg .= ' - ' . json_encode($result['error']);
        }

        return redirect()->back()->with('error', $errorMsg);
        // }

        // return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * Show purchase details
     */
    public function show($id)
    {
        // if (Auth::check() && Auth::user()->isAbleTo('sms_credit manage')) {
        $purchase = SmsCreditPurchase::where('client_id', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->findOrFail($id);

        return view('sms-credit::show', compact('purchase'));
        // }

        // return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * Payment callback from Hubtel
     */
    public function paymentCallback(Request $request)
    {
        $hubtel = new HubtelPaymentService();
        $verification = $hubtel->verifyCallback($request->all());

        if ($verification['status']) {
            $transactionId = $verification['transaction_id'];

            $purchase = SmsCreditPurchase::where('transaction_id', $transactionId)->first();

            if ($purchase && $purchase->status === 'pending') {
                $purchase->status = 'completed';
                $purchase->payment_response = json_encode($request->all());
                $purchase->save();

                // Add credits to balance
                $balance = SmsCreditBalance::getBalance($purchase->client_id, $purchase->workspace);
                $balance->addCredits(
                    $purchase->credits_purchased,
                    "Purchase #{$purchase->id} - {$purchase->credits_purchased} credits"
                );

                return response()->json(['status' => 'success', 'message' => 'Payment completed']);
            }
        }

        return response()->json(['status' => 'failed', 'message' => 'Payment verification failed']);
    }

    /**
     * Payment success page
     */
    public function paymentSuccess(Request $request)
    {
        return redirect()->route('sms-credit.index')
            ->with('success', __('Payment completed successfully. Your credits have been added to your account.'));
    }

    /**
     * Check payment status
     */
    public function checkStatus($id)
    {
        // if (Auth::check() && Auth::user()->isAbleTo('sms_credit manage')) {
        $purchase = SmsCreditPurchase::where('client_id', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->findOrFail($id);

        if ($purchase->transaction_id) {
            $hubtel = new HubtelPaymentService();
            $result = $hubtel->checkPaymentStatus($purchase->transaction_id);

            if ($result['status']) {
                $status = $result['data']['status'] ?? 'pending';

                if (strtolower($status) === 'success' && $purchase->status === 'pending') {
                    $purchase->status = 'completed';
                    $purchase->payment_response = json_encode($result['data']);
                    $purchase->save();

                    // Add credits
                    $balance = SmsCreditBalance::getBalance($purchase->client_id, $purchase->workspace);
                    $balance->addCredits(
                        $purchase->credits_purchased,
                        "Purchase #{$purchase->id}"
                    );

                    return redirect()->back()->with('success', __('Payment confirmed. Credits added to your account.'));
                } elseif (strtolower($status) === 'failed') {
                    $purchase->status = 'failed';
                    $purchase->save();

                    return redirect()->back()->with('error', __('Payment failed.'));
                }
            }
        }

        return redirect()->back()->with('info', __('Payment status: ') . $purchase->status);
        // }

        // return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * View balance and transactions
     */
    public function balance()
    {
        // if (Auth::check() && Auth::user()->isAbleTo('sms_credit manage')) {
        $balance = SmsCreditBalance::getBalance(creatorId(), getActiveWorkSpace());

        $transactions = \Workdo\SmsCredit\Entities\SmsCreditTransaction::where('client_id', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('sms-credit::balance', compact('balance', 'transactions'));
        // }

        // return redirect()->back()->with('error', __('Permission denied.'));
    }
}
