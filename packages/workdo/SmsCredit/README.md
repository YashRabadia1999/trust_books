# SMS Credit Module

## Overview
This module allows users to purchase SMS credits using Hubtel payment integration and manage their SMS credit balance.

## Features
- Purchase SMS credits via Hubtel Mobile Money payment
- Real-time credit calculation based on amount
- Track purchase history
- View credit balance and transaction history
- Automatic credit deduction when sending SMS
- Support for pending, completed, and failed payment statuses
- **Automatic SMS notifications for invoices and payments**
- Smart credit calculation based on message length

## Installation

### 1. Run Migration
```bash
php artisan migrate --path=packages/workdo/SmsCredit/src/Database/Migrations/2025_12_03_000000_create_sms_credit_tables.php
```

### 2. Configure Environment Variables
Add the following to your `.env` file:

```env
# Hubtel Payment Configuration (for credit purchases)
HUBTEL_API_KEY=your_hubtel_api_key
HUBTEL_API_SECRET=your_hubtel_api_secret
HUBTEL_BASE_URL=https://api.hubtel.com/v1/merchantaccount

# MNotify SMS API Configuration (for sending SMS)
MNOTIFY_API_KEY=your_mnotify_api_key
DEFAULT_SMS_SENDER=YourApp

# SMS Credit Configuration
SMS_RATE_PER_CREDIT=0.07
SMS_MIN_PURCHASE_AMOUNT=10
```

### 3. Register Service Provider
The service provider should be auto-discovered. If not, add to `config/app.php`:

```php
'providers' => [
    // ...
    Workdo\SmsCredit\Providers\SmsCreditServiceProvider::class,
],
```

### 4. Add Permissions
Add the following permissions in your system:
- `sms_credit manage` - View credit balance and purchase history
- `sms_credit create` - Purchase new credits

## Usage

### For Users

1. **Buy Credits**
   - Navigate to SMS Credits → Buy Credits
   - Enter the amount you want to pay (minimum GHS 10)
   - System automatically calculates credits (1 credit = GHS 0.07)
   - Enter your mobile money number
   - Click "Proceed to Payment"
   - Complete payment on your phone

2. **Check Balance**
   - Navigate to SMS Credits → My Balance
   - View total credits, used credits, and remaining credits
   - See transaction history

3. **Purchase History**
   - Navigate to SMS Credits → Purchase History
   - View all past purchases
   - Check payment status
   - Refresh pending payments

### For Developers

#### Check if user has sufficient credits
```php
use Workdo\SmsCredit\Helpers\SmsCreditHelper;

$hasCredits = SmsCreditHelper::hasCredits(100);
```

#### Use credits when sending SMS
```php
$success = SmsCreditHelper::useCredits(1, 'Sent SMS to 0501234567');
```

#### Get current balance
```php
$balance = SmsCreditHelper::getBalance();
```

#### Calculate credits needed
```php
$credits = SmsCreditHelper::calculateCreditsNeeded(250); // Message length
$bulkCredits = SmsCreditHelper::calculateBulkCredits(50, 150); // 50 recipients, 150 chars
```

## Integration with BulkSMS Module

To integrate credit checking before sending SMS, update your BulkSMS controllers:

```php
use Workdo\SmsCredit\Helpers\SmsCreditHelper;

// Before sending SMS
$creditsNeeded = SmsCreditHelper::calculateBulkCredits(count($recipients), strlen($message));

if (!SmsCreditHelper::hasCredits($creditsNeeded)) {
    return redirect()->back()->with('error', 'Insufficient SMS credits. Please purchase more credits.');
}

// After successful SMS send
SmsCreditHelper::useCredits($creditsNeeded, "Bulk SMS to {$groupName}");
```

## Automatic Invoice Notifications

The module automatically sends SMS notifications to customers when:

### 1. Invoice Created
When a new invoice is created, the customer receives:
```
Dear [Customer Name],

Invoice #[Invoice ID] has been created.
Amount: GHS [Total Amount]
Due Date: [Due Date]

Thank you for your business!
- [Company Name]
```

### 2. Payment Received
When a payment is added to an invoice, the customer receives:
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

### SMS Sending Logic
- Automatically triggered after invoice creation or payment
- Checks customer's mobile number (`mobile_no` or `billing_phone`)
- Calculates required credits before sending
- Deducts credits automatically on successful send
- Logs any errors without affecting invoice/payment creation

### Send SMS Manually
```php
use Workdo\SmsCredit\Services\SmsService;

// Send custom SMS
$result = SmsService::send('+233241234567', 'Your message');

// Send invoice notification
$result = SmsService::sendInvoiceCreatedSms($invoice, $customer);

// Send payment notification
$result = SmsService::sendPaymentReceivedSms($invoice, $payment, $customer);
```

## Database Tables

### sms_credit_purchases
Stores all credit purchase records
- Transaction details
- Payment status
- Hubtel transaction ID
- Mobile number used for payment

### sms_credit_balances
Stores current credit balance for each client/workspace
- Total credits purchased
- Used credits
- Remaining credits

### sms_credit_transactions
Detailed transaction log
- Purchase, usage, refund, adjustment types
- Credit amount (positive or negative)
- Description and reference

## Payment Flow

1. User enters amount and mobile number
2. System calculates credits and creates purchase record
3. Hubtel payment is initiated
4. User receives prompt on phone
5. User completes payment
6. Hubtel sends callback to system
7. System verifies payment and adds credits to balance
8. User can view updated balance

## Pricing

- **Rate per SMS:** GHS 0.07 per credit
- **Minimum purchase:** GHS 10.00
- **Credits calculation:** Amount ÷ 0.07 (rounded down)

Example:
- GHS 10.00 = 142 credits
- GHS 50.00 = 714 credits
- GHS 100.00 = 1,428 credits

## Support

For issues or questions, contact your system administrator.
