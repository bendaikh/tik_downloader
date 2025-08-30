# Demo User Guide

This document explains the demo user functionality implemented for CodeCanyon listings.

## Overview

The demo user system provides a restricted admin experience that prevents users from modifying critical settings while still allowing them to explore the application's features.

## Demo User Features

### ✅ Allowed Actions
- View analytics dashboard
- Configure proxy settings
- Manage appearance and themes
- Manage products
- Manage blog posts

### 🔒 Restricted Actions
- **Account Settings** - Cannot modify username, email, or password
- **AI Integration Settings** - Cannot modify OpenAI API keys or AI features
- **General Settings** - Cannot modify app name, description, or core settings
- **Payment Settings** - Cannot modify PayPal integration or donation settings
- **Google Analytics** - Cannot modify GA4 tracking settings
- **Google Search Console** - Cannot modify search console settings
- **Safari Analytics** - Cannot modify Safari analytics settings
- **Microsoft Services** - Cannot modify Bing, Clarity, or Azure settings
- **SEO Settings** - Cannot modify SEO meta tags or structured data

## Creating Demo Users

### Using the Demo User Command
```bash
php artisan make:demo-user --name="Demo Admin" --email="demo@example.com" --password="demo123456"
```

### Using the General User Command
```bash
php artisan make:user
# Choose 'demo' as the role when prompted
```

## Demo User Experience

### Visual Indicators
- **Demo Banner**: A prominent yellow banner appears at the top of the admin panel
- **Lock Icons**: Restricted menu items show 🔒 icons with "Demo Restricted" labels
- **Warning Messages**: Attempting to access restricted areas shows clear warning messages

### Security Features
- Middleware protection prevents demo users from accessing restricted controllers
- Form submissions to restricted endpoints are blocked
- Clear messaging explains why access is denied

## Implementation Details

### User Model
The `User` model includes:
- `is_demo` attribute that returns `true` when role is 'demo'
- `is_admin` attribute that returns `true` when role is 'admin'

### Middleware
- `AdminAccessMiddleware` trait provides `makeDemoRestrictionMiddleware()`
- Applied to all critical settings controllers
- Returns warning messages when demo users attempt access

### Controllers Protected
- `MeController` (Account Settings)
- `AIIntegrationController`
- `SettingsController`
- `PaymentSettingsController`
- `GoogleAnalyticsController`
- `GoogleSearchConsoleController`
- `SafariController`
- `EdgeIntegrationController`
- `SeoSettingsController`

## CodeCanyon Listing Benefits

1. **Security**: Prevents demo users from breaking the application
2. **Professional**: Shows the full admin interface without risk
3. **Transparent**: Clear indicators show what's restricted
4. **Functional**: Users can still explore most features
5. **Maintainable**: Easy to create and manage demo accounts

## Customization

### Adding New Restrictions
1. Add the middleware to the controller:
```php
use AdminAccessMiddleware;

public function __construct()
{
    $this->middleware($this->makeIsAdminMiddleware());
    $this->middleware($this->makeDemoRestrictionMiddleware());
}
```

2. Update the navigation component to show the restriction indicator

### Modifying Restrictions
Edit the `makeDemoRestrictionMiddleware()` method in `AdminAccessMiddleware` to change the warning message or add additional logic.

## Support

For questions about the demo user system, please refer to the main application documentation or contact support.
