# Custom Payment Methods System

## Overview
This system allows administrators to create fully customized payment methods with their own branding, forms, and styling through the admin dashboard. Each custom method is displayed beautifully to users with dynamic forms and attractive UI.

## Features
- 🎨 **Custom Branding**: Define primary and secondary colors for each payment method
- 🖼️ **Logo Upload**: Upload custom logos or use Material Design Icons
- 📝 **Dynamic Forms**: Create custom form fields (text, email, phone, number)
- 💅 **Custom Styling**: Add method-specific CSS for advanced customization
- 📋 **Instructions**: Provide HTML-formatted instructions to users
- 🔄 **Live Preview**: See exactly how your payment method will look while creating it
- 🎯 **Payment Types**: Support for card, PayPal, bank transfer, Paymee, and custom flows

## Database Structure

### PaymentMethod Model
Located at: `app/Models/PaymentMethod.php`

**Columns:**
- `key` - Unique identifier (e.g., 'paypal', 'stripe', 'custom_method')
- `name` - Display name shown to users
- `type` - Payment flow type (card, paypal, bank_transfer, paymee, custom, test)
- `icon` - MDI icon class (e.g., 'mdi-credit-card')
- `icon_path` - Path to uploaded logo image
- `description` - Brief description of the payment method
- `color_primary` - Primary brand color (hex)
- `color_secondary` - Secondary brand color (hex)
- `button_text` - Custom text for submit button
- `custom_form_fields` - JSON array of field definitions
- `custom_css` - Method-specific CSS styling
- `instructions_html` - HTML-formatted user instructions
- `active` - Enable/disable the method
- `sort_order` - Display order
- `meta` - Additional JSON metadata

## Admin Usage

### Creating a Payment Method

1. Navigate to `/admin/donations/methodes`
2. Click **"Ajouter Méthode"** button
3. Fill in the form:

#### Basic Info
- **Name**: Display name (e.g., "PayPal", "Stripe Card")
- **Key**: Unique identifier (auto-normalized to lowercase with underscores)
- **Type**: Select payment flow type
- **Description**: Brief explanation for users
- **Active**: Toggle to enable/disable

#### Design & Branding
- **Primary Color**: Main brand color (color picker)
- **Secondary Color**: Accent color (color picker)
- **Logo**: Upload PNG/JPG/SVG (max 4MB) OR use MDI icon
- **Button Text**: Customize submit button (default: "Pay now")

#### Custom Form Fields
- Click **"Add Field"** to create form inputs
- For each field:
  - **Label**: Field label shown to user
  - **Field Type**: text, email, tel, number
  - **Placeholder**: Hint text
  - **Required**: Toggle if field is mandatory
- Click ❌ to remove fields

#### Advanced
- **Instructions HTML**: Custom HTML shown to users (e.g., payment steps)
- **Custom CSS**: Method-specific styling (scoped to this method)

### Live Preview
The right panel shows a real-time preview of how users will see your payment method. Updates as you type!

## User-Facing Display

### How It Works
1. User selects payment method from dropdown
2. Custom method card appears with:
   - Logo/icon with brand colors
   - Method name and description
   - Instruction box (if provided)
   - Dynamic form fields
   - Styled submit button
3. Form includes all custom fields you defined
4. Submit button uses your custom text and colors

### Pages with Custom Methods
- `/donation.html` - Main donation page (authenticated)
- `/donations/create` - Simple donation form

## Technical Implementation

### Components

#### Blade Component
`resources/views/components/custom-payment-method.blade.php`

Usage:
```blade
<x-custom-payment-method :method="$paymentMethod" />
```

Props:
- `:method` - PaymentMethod model instance

#### Admin Modal
`resources/views/admin/donations/method-editor-modal.blade.php`

Features:
- Dynamic field builder with JavaScript
- Live preview system
- AJAX form submission
- Icon upload with preview

### JavaScript Integration

#### Method Selection Toggle
Located in `donation.blade.php` and `create.blade.php`

Logic:
1. Listen to payment method dropdown changes
2. Hide all custom method wrappers
3. Check selected method type
4. Show corresponding wrapper
5. Hide/show submit button accordingly

```javascript
methodSelect.addEventListener('change', function(){
  const selectedMethod = this.value;
  const methodType = this.options[this.selectedIndex].dataset.type;
  
  // Hide all custom wrappers
  document.querySelectorAll('.custom-payment-method-wrapper').forEach(el => el.style.display = 'none');
  
  // Show custom method if selected
  const customWrapper = document.querySelector(`.custom-payment-method-wrapper[data-method-key="${selectedMethod}"]`);
  if (customWrapper) {
    customWrapper.style.display = 'block';
  }
});
```

## Form Field Structure

Custom fields are stored as JSON array:

```json
[
  {
    "id": 1,
    "label": "PayPal Email",
    "type": "email",
    "placeholder": "your@email.com",
    "required": true
  },
  {
    "id": 2,
    "label": "Transaction Note",
    "type": "text",
    "placeholder": "Optional note",
    "required": false
  }
]
```

Field types supported:
- `text` - Single line text
- `email` - Email with validation
- `tel` - Phone number
- `number` - Numeric input

## Styling

### Default Styles
- Card with border-left accent in primary color
- Logo container: 56x56px with rounded corners
- Animated entrance (fadeInUp)
- Hover effect with subtle lift
- Color-coded instruction box

### Custom CSS
Add method-specific styles in the "Custom CSS" field:

```css
.custom-method-card {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.custom-method-card .form-control {
  border-radius: 12px;
}
```

CSS is automatically scoped to prevent conflicts.

## Backend Processing

### Validation
`app/Http/Controllers/DonationController.php`

Custom method keys are dynamically added to validation rules:

```php
$allowedMethods = ['virement_bancaire', 'test', 'carte', 'paypal', 'paymee'];
$customKeys = PaymentMethod::where('active', true)->pluck('key');
$allowedMethods = array_merge($allowedMethods, $customKeys->toArray());
```

### Storage
- Icons: `storage/app/public/payment_methods/`
- Public access: `public/storage/payment_methods/` (symlink)

Run: `php artisan storage:link` to create symlink

## Reserved Method Keys

These keys are reserved and cannot be modified through admin:
- `virement_bancaire` - Bank transfer
- `test` - Test/Mock payment

They are always displayed first and excluded from admin management.

## Examples

### Example 1: PayPal Integration
- **Name**: PayPal
- **Key**: paypal
- **Type**: paypal
- **Primary Color**: #0070BA
- **Secondary Color**: #003087
- **Icon**: mdi-paypal
- **Custom Fields**:
  - PayPal Email (email, required)
  - Transaction ID (text, optional)
- **Instructions**: "You will be redirected to PayPal to complete your payment securely."

### Example 2: Stripe Card
- **Name**: Credit/Debit Card
- **Key**: stripe_card
- **Type**: card
- **Primary Color**: #635BFF
- **Icon**: Upload Stripe logo
- **Custom Fields**:
  - Card Number (text, required)
  - Expiry Date (text, required)
  - CVV (number, required)
  - Cardholder Name (text, required)
- **Instructions**: "Your card information is securely processed by Stripe."

### Example 3: Mobile Money
- **Name**: Mobile Money
- **Key**: mobile_money
- **Type**: custom
- **Primary Color**: #FF6B00
- **Icon**: mdi-cellphone
- **Custom Fields**:
  - Phone Number (tel, required)
  - Network Provider (text, required)
- **Button Text**: "Send Payment Request"
- **Instructions**: "You will receive a payment request on your phone."

## Troubleshooting

### Icons not showing
- Run `php artisan storage:link`
- Check file permissions on `storage/app/public`
- Verify icon_path in database

### Custom method not appearing
- Check `active` column is `true`
- Verify method is not in reserved keys
- Clear browser cache

### Form submission not working
- Ensure all required fields are filled
- Check browser console for JavaScript errors
- Verify method validation in DonationController

### Live preview not updating
- Check browser console for errors
- Verify JavaScript is loaded
- Try refreshing the page

## Future Enhancements

Potential improvements:
- [ ] Edit existing payment methods
- [ ] Delete/archive methods
- [ ] Drag-and-drop field ordering
- [ ] Field validation rules (min/max length, regex)
- [ ] Conditional field display
- [ ] Multi-language support for fields
- [ ] Payment method usage analytics
- [ ] Webhook integration for custom flows
- [ ] Testing sandbox for payment flows

## Security Considerations

- Payment processing should be done server-side
- Never store sensitive card data
- Validate all custom field inputs
- Sanitize HTML instructions to prevent XSS
- Use HTTPS for all payment pages
- Implement CSRF protection (Laravel default)

## Support

For issues or questions:
1. Check this documentation
2. Review browser console for errors
3. Check Laravel logs: `storage/logs/laravel.log`
4. Verify database migrations: `php artisan migrate:status`

---

**Version**: 1.0  
**Last Updated**: 2025  
**Laravel Version**: 12.29.0
