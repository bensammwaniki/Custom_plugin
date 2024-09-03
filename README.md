# Kandara Registration Plugin

**Kandara Registration Plugin** is a custom WordPress plugin designed to handle user registrations, send SMS notifications via Africa's Talking API, and facilitate payments using the Safaricom M-Pesa payment gateway. This plugin allows you to manage user registrations seamlessly, send automated SMS notifications, and handle payments securely.

## Features

- **User Registration**: Register users with custom forms.
- **SMS Notifications**: Send SMS notifications to users via Africa's Talking API.
- **M-Pesa Integration**: Handle payments securely through Safaricom's M-Pesa API.
- **Admin Interface**: Manage and configure settings from the WordPress admin dashboard.
- **Database Setup**: Automatically creates necessary database tables during plugin activation.

## Requirements

- PHP 7.2 or higher
- WordPress 5.0 or higher
- Africa's Talking SDK (via Composer)
- Safaricom M-Pesa SDK (via Composer)
- Composer (for dependency management)

## Installation

### Step 1: Download and Install the Plugin
1. Clone or download the plugin's repository into the `wp-content/plugins/` directory of your WordPress installation.
2. Extract the contents if needed, so the plugin resides in its own folder, e.g., `wp-content/plugins/kandara-registration`.

### Step 2: Install Required Dependencies
This plugin uses Composer to manage external libraries (Africa's Talking SDK and Safaricom M-Pesa SDK). To install these dependencies, follow the steps below:

1. Navigate to the plugin's directory:
   ```bash
   cd wp-content/plugins/kandara-registration
   ```

2. Install the dependencies via Composer:
   ```bash
   composer install
   ```

### Step 3: Activate the Plugin
1. Log in to your WordPress admin dashboard.
2. Navigate to the **Plugins** section.
3. Locate the **Kandara Registration Plugin** and click **Activate**.

### Step 4: Configuration

#### Environment Variables
Create a `.env` file in the root of your plugin directory (where `composer.json` is located) and add the following environment variables:

```env
AFRICASTALKING_USERNAME=
AFRICASTALKING_API_KEY=

MPESA_CONSUMER_KEY=your_mpesa_consumer_key
MPESA_CONSUMER_SECRET=your_mpesa_consumer_secret
MPESA_ENV=sandbox # or live for production


```

#### Africa's Talking API Setup
- Set your Africa's Talking API credentials in the `.env` file.

#### M-Pesa API Setup
- Set your M-Pesa API credentials and environment (`sandbox` or `live`) in the `.env` file.

### Step 5: Register URLs for M-Pesa Integration
For M-Pesa integration to work properly, you need to set up the necessary callback URLs (e.g., validation and confirmation URLs) as per Safaricom's requirements. Register these URLs in your Safaricom Developer account for the C2B and B2C transactions.

## Usage

### Sending SMS Notifications
The plugin allows you to send SMS notifications to users via the Africa's Talking API. Here's how you can send an SMS programmatically within your plugin:

```php
function send_sms_notification($recipients, $message) {
    // Set your app credentials
    $username = getenv('KANDARA_USERNAME');
    $apiKey = getenv('KANDARA_API_KEY');
    
    // Initialize the SDK
    $AT = new AfricasTalking($username, $apiKey);
    $sms = $AT->sms();
    
    // Send SMS
    try {
        $result = $sms->send([
            'to' => $recipients,
            'message' => $message
        ]);
        return $result;
    } catch (Exception $e) {
        error_log('Error sending SMS: ' . $e->getMessage());
        return false;
    }
}
```

### Handling Payments with M-Pesa
This plugin integrates the Safaricom M-Pesa API to handle payments securely. Here's an example of how to initiate an M-Pesa payment:

```php
$mpesa = new \Safaricom\Mpesa\Mpesa();
$stkPushSimulation = $mpesa->STKPushSimulation(
    $BusinessShortCode,
    $LipaNaMpesaPasskey,
    $TransactionType,
    $Amount,
    $PartyA,
    $PartyB,
    $PhoneNumber,
    $CallBackURL,
    $AccountReference,
    $TransactionDesc,
    $Remarks
);
```

Refer to the Safaricom M-Pesa SDK documentation for more details on how to handle various types of transactions.

### Shortcodes
The plugin provides shortcodes to easily embed registration forms into your WordPress pages:

- `[kandara_registration_form]`: Displays the registration form for users.
- `[kandara_payment_form]`: Displays the payment form for users.

You can use these shortcodes in your posts or pages by simply placing them in the content editor.

### Admin Interface
The plugin includes an admin interface where you can configure plugin settings, view registration data, and manage SMS notifications.

## Database Setup
During plugin activation, the plugin automatically creates the necessary database tables to store registration data.

If you need to create the tables manually, refer to the `db-setup.php` file in the `includes` folder.

## Contributing
If you want to contribute to this plugin, feel free to fork the repository and submit pull requests. All contributions are welcome!

## License
This plugin is open-source and licensed under the MIT License.
