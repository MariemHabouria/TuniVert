# Payment Method Edit Feature - Implementation Summary

## Overview
The payment method edit functionality has been successfully implemented, allowing admins to modify existing payment methods through the same modal interface used for creation.

## What Was Added

### 1. Backend Routes & Controllers

#### New Routes (routes/web.php)
```php
Route::get('/methodes/{key}', [AdminController::class, 'donationsMethodesGet'])->name('methodes.get');
Route::put('/methodes/{key}', [AdminController::class, 'donationsMethodesUpdate'])->name('methodes.update');
```

#### New Controller Methods (AdminController.php)

**`donationsMethodesGet($key)`**
- Fetches a single payment method by key
- Returns JSON response with method data
- Used by edit modal to load existing data

**`donationsMethodesUpdate($key, Request $request)`**
- Updates existing payment method
- Supports key renaming
- Validates all fields (same as create)
- Handles icon file upload
- Returns updated method data

### 2. Frontend Changes

#### Modal Updates (method-editor-modal.blade.php)

**Dynamic Title & Button**
- Title changes between "Créer" and "Modifier"
- Submit button text updates for context
- Hidden fields track edit mode

**Hidden Fields Added**
```html
<input type="hidden" id="methodFormMethod" name="_method" value="">
<input type="hidden" id="editingMethodKey" value="">
```

**Global Variables**
- `window.fields` - Custom form fields array
- `window.renderFields()` - Re-render fields list
- `window.updatePreview()` - Update live preview

**Form Submit Handler**
- Detects PUT vs POST method
- Uses Laravel's method override (`_method`)
- Dynamic success message

#### Edit Function (methodes.blade.php)

**`editMethod(methodKey)` - Fully Implemented**
```javascript
async function editMethod(methodKey) {
    // 1. Fetch method data via AJAX
    const res = await fetch(`/admin/donations/methodes/${methodKey}`);
    const json = await res.json();
    const method = json.method;
    
    // 2. Update modal UI
    document.getElementById('modalTitleText').textContent = 'Modifier la Méthode';
    document.getElementById('submitBtnText').textContent = 'Mettre à Jour';
    
    // 3. Set form action and method
    form.action = `/admin/donations/methodes/${methodKey}`;
    document.getElementById('methodFormMethod').value = 'PUT';
    
    // 4. Populate all form fields
    form.querySelector('[name="method_name"]').value = method.name;
    form.querySelector('[name="method_key"]').value = method.key;
    // ... all other fields
    
    // 5. Populate custom fields
    if (method.custom_form_fields) {
        window.fields = JSON.parse(method.custom_form_fields);
        window.renderFields();
    }
    
    // 6. Show existing icon
    if (method.icon_path) {
        iconPreview.innerHTML = `<img src="/${method.icon_path}" ...>`;
    }
    
    // 7. Update preview and show modal
    window.updatePreview();
    modal.show();
}
```

**Reset Function for Create Mode**
- Attached to "Ajouter Méthode" button
- Resets form to POST action
- Clears all fields and preview
- Restores "Create" UI text

## How It Works

### User Flow: Editing a Method

1. **Admin clicks edit button** (pencil icon on method card)
   ```html
   <button onclick="editMethod('paypal22')">
       <i class="mdi mdi-pencil"></i>
   </button>
   ```

2. **AJAX request fetches method data**
   ```
   GET /admin/donations/methodes/paypal22
   Response: { ok: true, method: {...} }
   ```

3. **Modal opens with populated fields**
   - All text inputs filled
   - Checkboxes set correctly
   - Color pickers show existing colors
   - Custom fields loaded
   - Icon preview displayed
   - Live preview shows current design

4. **Admin makes changes**
   - Edit any field
   - Add/remove custom fields
   - Upload new icon
   - See live preview update

5. **Submit triggers PUT request**
   ```
   POST /admin/donations/methodes/paypal22
   Form Data: { _method: 'PUT', ... }
   ```

6. **Backend updates record**
   - Validates changes
   - Updates database
   - Returns updated method

7. **Success confirmation & page reload**
   ```
   ✅ Méthode modifiée avec succès: PayPal
   ```

### User Flow: Creating a Method

1. **Admin clicks "Ajouter Méthode"**
2. **Reset function runs** (clears any previous data)
3. **Modal opens empty** with "Créer" title
4. **Admin fills form**
5. **Submit triggers POST** to `/admin/donations/methodes`
6. **Backend creates new record**
7. **Success & reload**

## Field Mapping

### Basic Fields
| Database Column | Form Field Name | Type |
|----------------|-----------------|------|
| key | method_key | text |
| name | method_name | text |
| type | method_type | select |
| description | method_description | textarea |
| active | method_active | checkbox |
| icon | method_icon | text (MDI class) |
| icon_path | method_icon_file | file upload |

### Design Fields
| Database Column | Form Field Name | Type |
|----------------|-----------------|------|
| color_primary | color_primary | color |
| color_secondary | color_secondary | color |
| button_text | button_text | text |

### Advanced Fields
| Database Column | Form Field Name | Type |
|----------------|-----------------|------|
| custom_form_fields | custom_form_fields | JSON (hidden) |
| custom_css | custom_css | textarea |
| instructions_html | instructions_html | textarea |

## Custom Fields Structure

When editing, custom fields are reconstructed from JSON:

```javascript
// Stored in database as JSON string
"[{\"id\":1,\"label\":\"Email\",\"type\":\"email\",\"required\":true}]"

// Loaded into window.fields array
window.fields = [
    { id: 1, label: "Email", type: "email", required: true, placeholder: "" }
];

// Rendered in UI with edit controls
// Updates hidden input on change
// Shows in live preview
```

## Key Technical Details

### Laravel Method Override
Since HTML forms only support GET and POST, we use Laravel's method override:
```html
<input type="hidden" name="_method" value="PUT">
```
This tells Laravel to treat the POST request as PUT.

### AJAX with FormData
```javascript
const formData = new FormData(this);
formData.set('_method', 'PUT'); // Add method override

fetch(url, {
    method: 'POST', // Always POST
    body: formData
});
```

### Checkbox Handling
```javascript
// Reading in JavaScript
form.querySelector('[name="method_active"]').checked = method.active;

// Backend validation
$request->boolean('method_active'); // Handles checkbox quirks
```

### Icon Management
- **New icon uploaded**: Stores to `storage/public/payment_methods/`
- **No new icon**: Keeps existing `icon_path` or `icon` value
- **Preview**: Shows existing icon when editing

## Testing Checklist

- [x] Click edit button opens modal
- [x] Modal shows "Modifier" title
- [x] All fields populated with existing data
- [x] Custom fields loaded correctly
- [x] Existing icon displayed
- [x] Live preview shows current design
- [x] Changes reflected in preview immediately
- [x] Submit sends PUT request
- [x] Backend validates and updates
- [x] Success message shows "modifiée"
- [x] Page reloads with updated data
- [x] Click "Ajouter" resets modal to create mode
- [x] Create still works after using edit

## Future Enhancements

Possible improvements:
- [ ] Inline editing (edit directly on card)
- [ ] Duplicate method feature
- [ ] Version history / audit log
- [ ] Bulk edit multiple methods
- [ ] Import/export method configurations
- [ ] Method templates library
- [ ] A/B testing for methods
- [ ] Advanced icon editor (crop, filters)

## Troubleshooting

### Modal doesn't populate
**Cause**: AJAX request failed  
**Fix**: Check browser console, verify route exists, check network tab

### Submit creates instead of updates
**Cause**: `_method` not set to PUT  
**Fix**: Verify `methodFormMethod` input value is set in editMethod()

### Custom fields don't load
**Cause**: JSON parse error or fields not rendering  
**Fix**: Check `custom_form_fields` format in database, verify `window.renderFields()` called

### Icon doesn't show
**Cause**: Path incorrect or file not accessible  
**Fix**: Verify `icon_path` in database, check storage symlink exists

### Changes don't save
**Cause**: Validation error or permission issue  
**Fix**: Check Laravel logs, verify admin authentication, check validation rules

## API Reference

### GET /admin/donations/methodes/{key}
**Response:**
```json
{
    "ok": true,
    "method": {
        "key": "paypal",
        "name": "PayPal",
        "type": "paypal",
        "description": "Pay with PayPal",
        "active": true,
        "icon": "mdi-paypal",
        "icon_path": "storage/payment_methods/abc123.png",
        "color_primary": "#0070BA",
        "color_secondary": "#003087",
        "button_text": "Continue to PayPal",
        "custom_form_fields": "[{...}]",
        "custom_css": "...",
        "instructions_html": "..."
    }
}
```

### PUT /admin/donations/methodes/{key}
**Request:** FormData with all method fields + `_method=PUT`  
**Response:**
```json
{
    "ok": true,
    "method": { /* updated method data */ }
}
```

---

**Status**: ✅ Fully Implemented  
**Date**: 2025-10-05  
**Version**: 1.0
