<?php
/**
 * Plugin Name: Kandara Marathon
 * Description: Plugin for handling marathon and volunteer registrations.
 * Version: 3.0.0.2
 * Author: Bensam Mwaniki
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
require 'vendor/autoload.php';
use AfricasTalking\SDK\AfricasTalking;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(plugin_dir_path(__FILE__));
$dotenv->load();
// Include necessary files
include_once plugin_dir_path(__FILE__) . 'includes/enqueue.php';
include_once plugin_dir_path(__FILE__) . 'includes/shortcodes.php';
include_once plugin_dir_path(__FILE__) . 'includes/form-handlers.php';
include_once plugin_dir_path(__FILE__) . 'includes/admin.php';
include_once plugin_dir_path(__FILE__) . 'includes/db-setup.php';
require_once plugin_dir_path(__FILE__) . 'payment.php';


// Register activation hook
register_activation_hook(__FILE__, 'kandara_create_registration_table');

function send_sms_notification($recipients, $message) {
    // Set your app credentials
    $username = getenv('KANDARA_USERNAME');
    $apiKey = getenv('KANDARA_API_KEY');

    // Initialize the SDK
    $AT = new AfricasTalking($username, $apiKey);

    // Get the SMS service
    $sms = $AT->sms();

    // Set your shortCode or senderId
    $from = "KANDARARUN";

    try {
        // Send the SMS
        $result = $sms->send([
            'to'      => $recipients,
            'message' => $message,
            'from'    => $from
        ]);

        return $result;

    } catch (Exception $e) {
        return "Error: " . $e->getMessage();
    }
}

?>