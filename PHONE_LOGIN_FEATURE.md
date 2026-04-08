# Phone Number Login Feature

## Overview
The authentication system has been modified to allow users to log in using either their **email address** or **phone number** along with their password. **Mobile number is now a mandatory field** for all users during registration and profile updates.

## Changes Made

### 1. LoginRequest Validation (`app/Http/Requests/Auth/LoginRequest.php`)
- **Removed strict email validation**: Changed from `'email' => ['required', 'string', 'email']` to `'email' => ['required', 'string']`
- **Updated authenticate() method**: Now detects whether the input is a phone number or email using regex pattern matching
- **Dynamic field lookup**: Searches the database by `mobile_no` if phone format detected, otherwise by `email`
- **Better error messages**: Shows appropriate error message based on input type

### 2. Login View (`resources/views/auth/login.blade.php`)
- **Updated input label**: Changed from "Email" to "Email or Phone Number"
- **Changed input type**: From `type="email"` to `type="text"` to accept phone numbers
- **Updated placeholder**: Now shows "Email or Phone Number"

### 3. Mobile Number Now Mandatory
- **User Registration** (`RegisteredUserController.php`): Mobile number is required during signup
- **User Creation** (`UserController.php` - store method): Mobile number validation changed from `nullable` to `required`
- **User Update** (`UserController.php` - update method): Mobile number validation changed from `nullable` to `required`
- **Profile Edit** (`UserController.php` - editprofile method): Mobile number validation changed from `nullable` to `required`
- **Mobile Component** (`resources/views/components/mobile.blade.php`): Now shows required indicator and enforces required attribute
- **Registration Form** (`resources/views/auth/register.blade.php`): Added mobile number field with required validation
- **Profile Form** (`resources/views/users/profile.blade.php`): Mobile number field now required

### 4. Authentication Logic
The system now:
1. Detects if the input looks like a phone number using pattern: `/^[0-9+\-\(\)\s]+$/`
2. Searches for user by `mobile_no` if phone format, otherwise by `email`
3. Validates password against the matched user
4. Checks account status (enabled/disabled)
5. Attempts login with appropriate credentials

## How It Works

### Phone Number Detection
```php
$isPhone = preg_match('/^[0-9+\-\(\)\s]+$/', $loginField);
```
This regex pattern matches:
- Numbers: `0-9`
- Plus sign: `+` (for country codes like +233)
- Hyphens: `-`
- Parentheses: `()` (for area codes)
- Spaces: for formatting

### Examples of Valid Phone Formats
- `+8618673047576`
- `8618673047576`
- `+233 123 456 789`
- `(123) 456-7890`
- `123-456-7890`

### Examples of Valid Email Formats
- `user@example.com`
- `john.doe@company.org`
- `admin@domain.co.uk`

## Usage

### For End Users
1. Navigate to the login page
2. Enter either your **email address** OR **phone number** in the login field
3. Enter your **password**
4. Click **Login**

### Testing

#### Test with Email:
```
Login Field: user@example.com
Password: [user's password]
```

#### Test with Phone Number:
```
Login Field: +8618673047576
Password: [user's password]
```

## Database Requirements

### User Table Must Have:
- `email` column (existing, required)
- `mobile_no` column (**NOW REQUIRED** - must not be null)
- `password` column (hashed)
- `is_enable_login` column
- `is_disable` column

**Important Notes**: 
- 101 users currently have phone numbers in the system
- **New users MUST provide a mobile number during registration**
- **Existing users MUST add mobile number when updating their profile**
- Mobile number format: Must include country code (e.g., +91, +233, +1)
- Pattern validation: `^\+\d{1,3}\d{9,13}$`

## Validation Rules

All user creation and update operations now require mobile number:

```php
'mobile_no' => 'required|regex:/^\+\d{1,3}\d{9,13}$/'
```

This applies to:
- User Registration (new accounts)
- User Creation by admin
- User Profile Updates
- User Edit by admin

## Security Features

### Rate Limiting
- Still applies to both email and phone login attempts
- Uses the input value + IP address for throttling
- Default: 5 attempts before lockout

### Account Status Checks
The system verifies:
```php
if($user->is_enable_login != 1 || $user->is_disable != 1 && $user->type != "super admin")
```
- Account must be enabled for login
- Account must not be disabled
- Super admin accounts have special handling

### Password Verification
- Uses `password_verify()` for secure password checking
- Passwords are hashed in database
- Multiple users can have same email (different workspaces) - system checks all and verifies password

## Error Messages

| Scenario | Error Message |
|----------|---------------|
| Phone number not found | "This phone number doesn't match" |
| Email not found | "This email doesn't match" |
| Account disabled | "Your account is disabled from company" |
| Wrong password | "These credentials do not match our records." |
| Rate limited | "Too many login attempts. Please try again in X seconds." |

## Backward Compatibility

✅ **Fully backward compatible**
- Existing email-based logins continue to work
- No changes needed to existing user accounts
- Users can choose to use either email or phone number

## Technical Details

### Modified Files:
1. `app/Http/Requests/Auth/LoginRequest.php`
   - Updated `rules()` method
   - Updated `authenticate()` method
   
2. `resources/views/auth/login.blade.php`
   - Updated input field label and type

3. `app/Http/Controllers/UserController.php` ⭐ NEW
   - Changed mobile_no validation from `nullable` to `required` in store() method
   - Changed mobile_no validation from `nullable` to `required` in update() method
   - Changed mobile_no validation from `nullable` to `required` in editprofile() method

4. `app/Http/Controllers/Auth/RegisteredUserController.php` ⭐ NEW
   - Added `mobile_no` to registration validation rules (required)
   - Added `mobile_no` to User::create() array

5. `resources/views/components/mobile.blade.php` ⭐ NEW
   - Made component always show required indicator
   - Set `required='required'` attribute permanently

6. `resources/views/auth/register.blade.php` ⭐ NEW
   - Added mobile number input field with validation
   - Required field with pattern matching

7. `resources/views/users/profile.blade.php` ⭐ NEW
   - Added required attribute to mobile number field
   - Added pattern validation and required indicator

### Cache Cleared:
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## Troubleshooting

### Issue: Login not working with phone number
**Solution**: Ensure the user has a valid `mobile_no` in the database

### Issue: Phone number format not recognized
**Solution**: The phone number should contain only digits, +, -, (), or spaces

### Issue: Multiple users with same phone number
**Solution**: System will check password for all matching users and login the first valid match

### Issue: "Account disabled" error
**Solution**: Check `is_enable_login` and `is_disable` columns in users table

## Future Enhancements

Possible improvements:
- [ ] Add phone number validation on registration
- [ ] Support for international phone number formatting
- [ ] OTP/SMS verification for phone login
- [ ] Display phone number on profile if used for login
- [ ] Add "Login method" indicator in admin panel

## Examples

### Success Flow (Phone Number):
1. User enters: `+8618673047576`
2. System detects phone format
3. Searches: `User::where('mobile_no', '+8618673047576')`
4. Finds user, verifies password
5. Checks account status
6. Logs in successfully

### Success Flow (Email):
1. User enters: `user@example.com`
2. System detects email format
3. Searches: `User::where('email', 'user@example.com')`
4. Finds user, verifies password
5. Checks account status
6. Logs in successfully

## Support

If you encounter any issues with the phone login feature:
1. Verify the user has a `mobile_no` in the database
2. Check that the phone number format is valid
3. Ensure account is enabled (`is_enable_login = 1`, `is_disable = 0`)
4. Clear application cache if changes aren't reflecting
5. Check Laravel logs in `storage/logs/laravel.log`

---

**Feature Status**: ✅ Active and Ready to Use  
**Mobile Number**: 🔴 **MANDATORY FIELD** - Required for all users  
**Last Updated**: December 5, 2025

## Migration Notes for Existing Users

If you have existing users without mobile numbers:

1. **Users cannot update their profile without adding a mobile number**
2. **Admins cannot create new users without a mobile number**
3. **New registrations require mobile number**

### For Existing Users Without Mobile Numbers:

Users who don't have mobile numbers will need to:
1. Contact administrator to add mobile number to their account
2. Or update via database directly (if admin access available)

### SQL to Check Users Without Mobile:
```sql
SELECT id, name, email, mobile_no 
FROM users 
WHERE mobile_no IS NULL OR mobile_no = '';
```

### SQL to Update (Admin Only):
```sql
UPDATE users 
SET mobile_no = '+1234567890' 
WHERE id = [user_id];
```
