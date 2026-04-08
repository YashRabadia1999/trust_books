# SMS Integration with Invoice Module - Implementation Summary

## What Was Implemented

### 1. SMS Service (`SmsService.php`)
Created a comprehensive SMS service that:
- Sends SMS via MNotify API
- Checks user's SMS credit balance before sending
- Automatically deducts credits after successful SMS delivery
- Formats mobile numbers to international format (+233...)
- Provides two specialized methods for invoice notifications

**Location:** `packages/workdo/SmsCredit/src/Services/SmsService.php`

### 2. Invoice Controller Updates
Updated `InvoiceController.php` to automatically send SMS:

#### A. After Invoice Creation
- Added SMS notification in `storeProductInvoice()` method
- Sends SMS with invoice details (number, amount, due date)
- Only sends if SmsCredit module is active
- Errors are logged but don't stop invoice creation

#### B. After Payment Received
- Added SMS notification in `createPayment()` method
- Sends SMS with payment details (amount paid, total paid, balance due)
- Only sends if SmsCredit module is active
- Errors are logged but don't stop payment processing

**Location:** `app/Http/Controllers/InvoiceController.php`

### 3. SMS Message Templates

#### Invoice Created Message:
```
Dear [Customer Name],

Invoice #[Invoice ID] has been created.
Amount: GHS [Total Amount]
Due Date: [Due Date]

Thank you for your business!
- [Company Name]
```

#### Payment Received Message:
```
Dear [Customer Name],

Payment received for Invoice #[Invoice ID]

Invoice Amount: GHS [Total]
Paid Now: GHS [Payment Amount]
Total Paid: GHS [Total Paid]
Balance Due: GHS [Remaining Due]

Thank you for your payment!
- [Company Name]
```

## Configuration Required

### 1. Update `.env` File
Add these environment variables:

```env
# MNotify SMS API (for sending messages)
MNOTIFY_API_KEY=your_mnotify_api_key
DEFAULT_SMS_SENDER=YourApp
```

### 2. Get MNotify API Key
1. Login to your MNotify account at https://www.mnotify.com
2. Navigate to API Settings
3. Copy your API Key
4. Register and verify your Sender ID

## How It Works

### Credit Management Flow

1. **Before Sending SMS:**
   - Calculate credits needed based on message length
   - Check if user has sufficient credits
   - If insufficient, SMS is not sent (error logged)

2. **SMS Credit Calculation:**
   - First 150 characters = 1 credit
   - Every additional 100 characters = 1 credit
   - Example: 200-char message = 2 credits

3. **After SMS Sent:**
   - Credits automatically deducted from user's balance
   - Transaction logged in `sms_credit_transactions` table
   - Description includes recipient number

### Automatic Triggering

**Invoice Creation:**
```php
// In storeProductInvoice() method
event(new CreateInvoice($request, $invoice));

// NEW: Send SMS notification
if (module_is_active('SmsCredit')) {
    try {
        $customerUser = User::find($invoice->user_id);
        if ($customerUser) {
            SmsService::sendInvoiceCreatedSms($invoice, $customerUser);
        }
    } catch (\Exception $e) {
        \Log::error('Invoice SMS Error: ' . $e->getMessage());
    }
}
```

**Payment Creation:**
```php
// In createPayment() method
event(new CreatePaymentInvoice($request, $invoice, $invoicePayment));

// NEW: Send SMS notification
if (module_is_active('SmsCredit')) {
    try {
        $customerUser = User::find($invoice->user_id);
        if ($customerUser) {
            SmsService::sendPaymentReceivedSms($invoice, $invoicePayment, $customerUser);
        }
    } catch (\Exception $e) {
        \Log::error('Payment SMS Error: ' . $e->getMessage());
    }
}
```

## Testing

### 1. Test Invoice Creation
1. Ensure you have SMS credits (buy via `/sms-credit/create`)
2. Create a new invoice
3. Check customer receives SMS
4. Verify credits deducted from balance

### 2. Test Payment Addition
1. Have an existing invoice
2. Add a payment
3. Check customer receives payment SMS
4. Verify credits deducted

### 3. Check Logs
Monitor `storage/logs/laravel.log` for any SMS-related errors:
```
tail -f storage/logs/laravel.log | grep SMS
```

## Mobile Number Requirements

For SMS to send successfully, customers must have:
- `mobile_no` field populated, OR
- `billing_phone` field populated

The system checks both fields and uses the first available.

## Error Handling

The integration includes robust error handling:

1. **No Mobile Number**: SMS skipped, logged as error
2. **Insufficient Credits**: SMS not sent, error returned
3. **API Failure**: Error logged, invoice/payment still created
4. **Module Disabled**: SMS skipped entirely

All errors are logged to `storage/logs/laravel.log` but don't interrupt normal operations.

## Database Tables Used

### sms_credit_balances
- Stores user's credit balance
- Updated when SMS sent

### sms_credit_transactions
- Logs every credit usage
- Type: 'usage'
- Description: "SMS sent to +233..."

## Files Modified/Created

### Created:
1. `packages/workdo/SmsCredit/src/Services/SmsService.php`

### Modified:
1. `app/Http/Controllers/InvoiceController.php`
   - Added `use Workdo\SmsCredit\Services\SmsService;`
   - Updated `storeProductInvoice()` method
   - Updated `createPayment()` method

2. `packages/workdo/SmsCredit/README.md`
   - Added automatic notification documentation

3. `.env.example`
   - Added SMS API configuration variables

## Next Steps

1. **Configure MNotify SMS API:**
   - Add MNOTIFY_API_KEY to `.env`
   - Test with a sample invoice

2. **Purchase SMS Credits:**
   - Buy credits via `/sms-credit/create`
   - Minimum GHS 10

3. **Test the Flow:**
   - Create a test invoice
   - Add a test payment
   - Verify SMS received

4. **Monitor Usage:**
   - Check credit balance regularly
   - Review transaction logs
   - Top up credits as needed

## Troubleshooting

### SMS Not Sending?

1. **Check Credits:**
   ```
   Visit /sms-credit/balance
   ```

2. **Check Customer Mobile:**
   ```sql
   SELECT mobile_no, billing_phone FROM users WHERE id = [customer_id];
   ```

3. **Check Logs:**
   ```bash
   tail -n 50 storage/logs/laravel.log
   ```

4. **Verify API Credentials:**
   ```
   Check .env for MNOTIFY_API_KEY and DEFAULT_SMS_SENDER
   ```

## Support

For issues:
1. Check `storage/logs/laravel.log` for error details
2. Verify MNotify API credentials
3. Ensure sufficient SMS credits
4. Confirm customer has valid mobile number
