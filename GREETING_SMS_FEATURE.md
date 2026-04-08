# Greeting SMS Feature - BulkSMS Module

## Overview

A powerful bulk messaging feature that allows companies to send personalized greeting SMS messages to selected users. Perfect for seasonal greetings, appreciation messages, and custom announcements.

## Features

✅ **User Selection** - Select multiple users from your organization
✅ **Predefined Templates** - Quick-start templates for different occasions
✅ **Message Personalization** - Dynamic variables like {name}, {company}, etc.
✅ **SMS Credit Integration** - Automatic credit calculation and deduction
✅ **Real-time Preview** - See how your message will look before sending
✅ **Notification Settings** - Respects SMS notification preferences
✅ **Comprehensive Logging** - Track all sent messages and failures

## Access

**URL:** `/bulksms/greeting`

**Permission Required:** `bulksms manage`

**Module Required:** BulkSMS (must be active)

## How to Use

### 1. Access the Feature
Navigate to: **Bulk SMS → Greeting SMS**

### 2. Select Greeting Type
Choose from three types:
- **Seasonal Greetings** - New Year, Christmas, Easter, etc.
- **General Messages** - Thank you, welcome, appreciation
- **Custom Message** - Write your own from scratch

### 3. Select a Template (Optional)
- Templates are auto-loaded based on greeting type
- Click to instantly fill the message field
- Or write your own custom message

### 4. Compose Your Message
- Minimum: 10 characters
- Maximum: 500 characters
- Use variables for personalization:
  - `{name}` - Full name
  - `{first_name}` - First name only
  - `{email}` - Email address
  - `{company}` - Company name

### 5. Select Recipients
- View all users with mobile numbers
- Filter shows: Name, Mobile, Type, Status
- Disabled users cannot be selected
- Use "Select All" / "Deselect All" buttons

### 6. Review & Send
- Check message preview
- View SMS credit calculation
- Confirm total credits to be used
- Click "Send SMS"

## Message Templates

### Seasonal Greetings

**New Year:**
```
🎊 Happy New Year, {name}!

Wishing you a year filled with success, happiness, and prosperity.

Best wishes from {company}!
```

**Christmas:**
```
🎄 Merry Christmas, {name}!

May this festive season bring you joy and wonderful moments.

Warm wishes from {company}!
```

**Easter:**
```
🐰 Happy Easter, {name}!

Wishing you a blessed Easter filled with hope and renewal.

Best regards from {company}!
```

### General Messages

**Thank You:**
```
Dear {name},

Thank you for being a valued member of our community. We appreciate your continued support.

Best regards,
{company}
```

**Welcome:**
```
Hello {first_name}! 👋

Welcome to {company}! We're excited to have you with us.

Feel free to reach out if you need any assistance.
```

**Appreciation:**
```
Dear {name},

We wanted to take a moment to appreciate your dedication and hard work.

Thank you for all you do!

- {company} Team
```

## SMS Credit Calculation

Credits are calculated based on message length:
- **First 150 characters:** 1 credit
- **151-250 characters:** 2 credits
- **251-350 characters:** 3 credits
- **351-450 characters:** 4 credits
- **451-500 characters:** 5 credits

**Example:**
- 10 users selected
- Message: 180 characters (2 credits per user)
- **Total:** 20 SMS credits will be deducted

## Variables Explained

| Variable | Replaces With | Example |
|----------|---------------|---------|
| `{name}` | User's full name | "John Doe" |
| `{first_name}` | First name only | "John" |
| `{email}` | Email address | "john@example.com" |
| `{company}` | Company name | "Acme Corp" |

**Sample Message:**
```
Hello {first_name},

Thank you for being part of {company}.

Best regards,
{name} Team
```

**After Processing:**
```
Hello John,

Thank you for being part of Acme Corp.

Best regards,
John Doe Team
```

## Prerequisites

### 1. BulkSMS Module Active
```bash
# Check if module is active
SELECT * FROM modules WHERE name = 'BulkSMS' AND is_active = 1;
```

### 2. SmsCredit Module Active
Required for sending SMS and credit deduction.

### 3. Notification Settings
Enable in Settings → SMS Notifications:
- "Seasonal Greetings" - for seasonal messages
- "Birthday Wishes" - for general/custom messages

### 4. SMS Credits Available
Ensure sufficient credits in your account:
- Visit: `/sms-credit/balance`
- Purchase: `/sms-credit/create`

### 5. User Mobile Numbers
Users must have `mobile_no` field populated (international format).

## File Locations

**Controller:**
```
packages/workdo/BulkSMS/src/Http/Controllers/GreetingSmsController.php
```

**View:**
```
packages/workdo/BulkSMS/src/Resources/views/greeting/index.blade.php
```

**Routes:**
```php
// packages/workdo/BulkSMS/src/Routes/web.php
Route::get('bulksms/greeting', [GreetingSmsController::class, 'index']);
Route::post('bulksms/greeting/send', [GreetingSmsController::class, 'send']);
Route::get('bulksms/greeting/templates', [GreetingSmsController::class, 'getTemplates']);
```

## Error Handling

The system handles various error scenarios:

**Insufficient Credits:**
```
Error: Insufficient SMS credits. Required: 20
```

**No Users Selected:**
```
Error: No valid users selected.
```

**Notification Disabled:**
```
Error: Greeting SMS notification is disabled. Please enable it in SMS settings.
```

**Module Inactive:**
```
Error: SmsCredit module is not active. Please activate it to send SMS.
```

## Success Response

```
SMS sending completed. Successful: 8, Failed: 2
Errors: John Doe: Insufficient credits; Jane Smith: Invalid mobile number...
```

## Logging

All SMS sends are logged in `storage/logs/laravel.log`:

**Success:**
```
[2025-12-03 19:00:00] production.INFO: Greeting SMS sent to John Doe (ID: 123)
```

**Failure:**
```
[2025-12-03 19:00:01] production.ERROR: Greeting SMS failed for Jane Smith (ID: 456): Insufficient SMS credits
```

## Database Impact

### Tables Modified:
- `sms_credit_balances` - Credits deducted
- `sms_credit_transactions` - Usage logged

### Sample Transaction:
```sql
INSERT INTO sms_credit_transactions VALUES (
  credits: -2,
  description: 'SMS sent to +233240123456',
  type: 'usage',
  user_id: 1,
  workspace_id: 1
);
```

## Best Practices

1. **Test First** - Send to yourself before bulk sending
2. **Check Credits** - Ensure sufficient balance
3. **Personalize** - Use variables for better engagement
4. **Review Preview** - Always check message preview
5. **Monitor Logs** - Review logs after bulk sends
6. **Update Templates** - Keep seasonal messages current

## Troubleshooting

### Messages Not Sending?

**1. Check Module Status:**
```bash
php artisan module:list | grep BulkSMS
```

**2. Check SMS Credits:**
Visit `/sms-credit/balance`

**3. Check Notification Settings:**
Settings → SMS Notifications → Enable "Seasonal Greetings"

**4. Check User Mobile Numbers:**
```sql
SELECT id, name, mobile_no 
FROM users 
WHERE created_by = [company_id] 
AND mobile_no IS NOT NULL;
```

**5. Check Logs:**
```bash
tail -f storage/logs/laravel.log | grep "Greeting SMS"
```

### Common Issues

**Issue:** "Permission denied"
**Solution:** Ensure user has `bulksms manage` permission

**Issue:** "No users found"
**Solution:** Users need mobile numbers populated

**Issue:** Credits deducted but SMS not sent
**Solution:** Check MNotify API configuration in `.env`

## Security

- **Permission Check:** Only users with `bulksms manage` permission
- **Workspace Isolation:** Only send to users in active workspace
- **Input Validation:** All inputs sanitized and validated
- **Credit Check:** Pre-send validation of available credits
- **Rate Limiting:** Built into SmsService

## API Reference

### Get Templates
```http
GET /bulksms/greeting/templates?type=seasonal
```

**Response:**
```json
{
  "success": true,
  "templates": [
    {
      "name": "New Year Greeting",
      "message": "🎊 Happy New Year, {name}!..."
    }
  ]
}
```

## Future Enhancements

Possible future features:
- Schedule messages for later
- Save custom templates
- SMS history and analytics
- Recurring greeting schedules
- Export delivery reports

## Support

For issues or questions:
1. Check logs: `storage/logs/laravel.log`
2. Verify SMS credits available
3. Confirm notification settings enabled
4. Review user mobile number format

---

**Created:** December 3, 2025  
**Version:** 1.0  
**Module:** BulkSMS - Greeting SMS Feature
