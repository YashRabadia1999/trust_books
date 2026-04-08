# Birthday SMS Greeting - Automated Cron Job

## Overview

An automated system that sends birthday SMS greetings to users on their birthday. The system uses the SmsCredit module to deduct credits for each SMS sent.

## Features

- ✅ Automatically sends birthday SMS to users on their birthday
- ✅ Deducts SMS credits from company balance
- ✅ Personalized message with user's name
- ✅ Calculates and includes age if birth year is available
- ✅ Runs daily at 9:00 AM (configurable)
- ✅ Comprehensive logging and error handling
- ✅ Only sends to active users with mobile numbers

## Command

### Command Signature
```bash
php artisan birthday:send-sms
```

### Location
`app/Console/Commands/SendBirthdaySms.php`

## How It Works

### 1. Daily Schedule
The command runs automatically every day at 9:00 AM (Africa/Accra timezone).

**Schedule Configuration:** `routes/console.php`
```php
Schedule::command('birthday:send-sms')
    ->dailyAt('09:00')
    ->timezone('Africa/Accra')
    ->withoutOverlapping()
    ->runInBackground();
```

### 2. User Selection
The system finds users who:
- Have a birthday date set
- Have a mobile number
- Birthday matches today's date (month and day)
- Are active (not disabled)

### 3. SMS Message
Each birthday user receives a personalized SMS:

**Sample Message:**
```
🎉 Happy Birthday, John Doe! 🎂

Wishing you a wonderful 30th birthday!

May this special day bring you joy, happiness, and all the wonderful things you deserve.

Best wishes from all of us at [Company Name]!
```

**Message Components:**
- User's name
- Age (if birth year is valid)
- Company name from settings
- Birthday emoji and celebration message

### 4. SMS Credit Deduction
- Each SMS automatically deducts credits from the company's SMS balance
- Credits calculated based on message length (1 credit per 150 characters)
- Transaction logged in `sms_credit_transactions` table
- If insufficient credits, SMS is not sent and error is logged

## Prerequisites

### 1. SmsCredit Module
The SmsCredit module must be active:
```bash
# Check if module is active
SELECT * FROM modules WHERE name = 'SmsCredit' AND is_active = 1;
```

### 2. User Requirements
Users must have:
- Birthday field populated in database
- Valid mobile number (in international format)
- Active status (`is_disable = 0`)

### 3. SMS Credits
Ensure sufficient SMS credits are available:
```
Visit: /sms-credit/balance
Buy credits: /sms-credit/create
```

### 4. MNotify API Configuration
Verify `.env` has MNotify credentials:
```env
MNOTIFY_API_KEY=your_mnotify_api_key
DEFAULT_SMS_SENDER=YourApp
```

## Manual Testing

### Test the Command Manually
```bash
php artisan birthday:send-sms
```

### Check Output
```
Starting birthday SMS sending process...
Found 3 users with birthdays today.
✓ Birthday SMS sent to John Doe (+233240123456)
✓ Birthday SMS sent to Jane Smith (+233201234567)
✗ Failed to send SMS to Bob Johnson: Insufficient SMS credits

==================================================
Birthday SMS Summary:
Total Users: 3
Successfully Sent: 2
Failed: 1
==================================================
```

### Test with Specific User
To test, temporarily set a user's birthday to today:
```sql
UPDATE users 
SET birthday = CURDATE() 
WHERE id = 1;
```

Then run:
```bash
php artisan birthday:send-sms
```

## Setting Up the Cron Job

### For Linux/Ubuntu Server

1. **Edit Crontab:**
   ```bash
   crontab -e
   ```

2. **Add Laravel Scheduler:**
   ```
   * * * * * cd /path/to/dss && php artisan schedule:run >> /dev/null 2>&1
   ```

3. **Verify Cron is Running:**
   ```bash
   crontab -l
   ```

### For Windows Server

1. **Open Task Scheduler**

2. **Create Basic Task:**
   - Name: "Laravel Birthday SMS Scheduler"
   - Trigger: Daily at startup
   - Action: Start a program

3. **Program/Script:**
   ```
   C:\xampp\php\php.exe
   ```

4. **Arguments:**
   ```
   artisan schedule:run
   ```

5. **Start in:**
   ```
   C:\xampp\htdocs\dss
   ```

6. **Repeat Task Every:** 1 minute for duration of 1 day

### For Development (Windows with XAMPP)

Run scheduler manually:
```bash
# Keep this running in background
php artisan schedule:work
```

Or set up Windows Task Scheduler as described above.

## Monitoring & Logs

### Check Laravel Logs
```bash
tail -f storage/logs/laravel.log | grep Birthday
```

### Log Entries
**Success:**
```
[2025-12-03 09:00:15] local.INFO: Birthday SMS sent to John Doe (ID: 123)
[2025-12-03 09:00:16] local.INFO: Birthday SMS cron completed. Success: 5, Failed: 0
```

**Failure:**
```
[2025-12-03 09:00:15] local.ERROR: Birthday SMS failed for Jane Smith (ID: 456): Insufficient SMS credits
```

### Database Monitoring

**Check SMS Transactions:**
```sql
SELECT * FROM sms_credit_transactions 
WHERE description LIKE '%Birthday%' 
ORDER BY created_at DESC 
LIMIT 10;
```

**Check Credit Balance:**
```sql
SELECT * FROM sms_credit_balances 
WHERE user_id = [company_id];
```

## Configuration Options

### Change Schedule Time
Edit `routes/console.php`:
```php
// Send at 8:00 AM instead
Schedule::command('birthday:send-sms')
    ->dailyAt('08:00')
    ->timezone('Africa/Accra');

// Or send twice daily (morning and afternoon)
Schedule::command('birthday:send-sms')
    ->twiceDaily(9, 14);
```

### Customize Birthday Message
Edit `app/Console/Commands/SendBirthdaySms.php` in the `prepareBirthdayMessage()` method:
```php
private function prepareBirthdayMessage(User $user)
{
    // Your custom message format
    $message = "Happy Birthday {$user->name}! 🎉\n";
    $message .= "Your custom message here...";
    return $message;
}
```

## SMS Credit Costs

Birthday SMS credits are calculated based on message length:
- **First 150 characters:** 1 credit
- **151-250 characters:** 2 credits  
- **251-350 characters:** 3 credits

**Typical Birthday SMS:** ~200 characters = **2 credits** per message

**Monthly Estimate:**
If you have 100 employees:
- Average 8 birthdays per month (100/12)
- 8 messages × 2 credits = **16 credits/month**
- Cost: ~GHS 1.60/month (at GHS 0.10/credit)

## Troubleshooting

### SMS Not Sending?

**1. Check if module is active:**
```bash
php artisan tinker
>>> module_is_active('SmsCredit')
```

**2. Check user has birthday and mobile:**
```sql
SELECT id, name, birthday, mobile_no, is_disable 
FROM users 
WHERE birthday IS NOT NULL 
AND mobile_no IS NOT NULL;
```

**3. Check SMS credits:**
```
Visit: /sms-credit/balance
```

**4. Check logs:**
```bash
tail -f storage/logs/laravel.log
```

**5. Test command manually:**
```bash
php artisan birthday:send-sms
```

### Cron Not Running?

**Linux:**
```bash
# Check cron service
sudo service cron status

# Check cron logs
grep CRON /var/log/syslog
```

**Windows:**
- Open Task Scheduler
- Check task history
- Ensure PHP path is correct

### Common Issues

**Issue:** "SmsCredit module is not active"
**Solution:** Activate the module in admin panel

**Issue:** "Insufficient SMS credits"
**Solution:** Purchase more credits at `/sms-credit/create`

**Issue:** "Customer mobile number not found"
**Solution:** Ensure users have mobile_no field populated

**Issue:** Schedule not running
**Solution:** Ensure Laravel scheduler is set up in cron/Task Scheduler

## Testing Scenarios

### Scenario 1: Test with Today's Birthday
```sql
-- Set test user's birthday to today
UPDATE users SET birthday = CURDATE() WHERE id = 1;

-- Run command
php artisan birthday:send-sms

-- Reset birthday
UPDATE users SET birthday = '1990-06-15' WHERE id = 1;
```

### Scenario 2: Test Insufficient Credits
```sql
-- Set balance to 0
UPDATE sms_credit_balances SET balance = 0 WHERE user_id = [company_id];

-- Try sending
php artisan birthday:send-sms

-- Should see "Insufficient credits" error
```

### Scenario 3: Test Multiple Birthdays
```sql
-- Set multiple users' birthdays to today
UPDATE users SET birthday = CURDATE() WHERE id IN (1, 2, 3);

-- Run command
php artisan birthday:send-sms

-- Check summary shows all users
```

## Database Schema

### Required Fields in `users` Table
- `id` - User ID
- `name` - User's full name
- `birthday` - Date field (YYYY-MM-DD) - **NULLABLE**
- `mobile_no` - Phone number (international format)
- `is_disable` - Active status (0 = active)
- `created_by` - Company/creator ID
- `workspace_id` - Workspace ID

### SMS Credit Tables Used
- `sms_credit_balances` - Tracks credit balance
- `sms_credit_transactions` - Logs credit usage

## Best Practices

1. **Monitor Credits:** Set up alerts when credits are low
2. **Test First:** Run manual tests before relying on automation
3. **Check Logs:** Review logs weekly to ensure smooth operation
4. **Update Messages:** Keep birthday messages fresh and personalized
5. **Timezone:** Ensure correct timezone in schedule configuration
6. **Backup Plan:** Have manual process ready if automation fails

## Support & Maintenance

### Regular Checks
- Weekly: Review logs for any errors
- Monthly: Verify all birthdays were sent
- Quarterly: Review and update birthday message template

### Updates
When updating the system:
1. Test command manually after updates
2. Verify cron schedule still runs
3. Check logs for any new errors

## Related Documentation
- [SMS Integration Summary](SMS_INTEGRATION_SUMMARY.md)
- [SmsCredit Module](packages/workdo/SmsCredit/README.md)

## Command Reference

```bash
# Run birthday SMS command manually
php artisan birthday:send-sms

# View scheduled commands
php artisan schedule:list

# Run scheduler once
php artisan schedule:run

# Keep scheduler running (development)
php artisan schedule:work

# Clear all caches
php artisan optimize:clear
```

---

**Created:** December 3, 2025  
**Version:** 1.0  
**Module:** Birthday SMS Automation
