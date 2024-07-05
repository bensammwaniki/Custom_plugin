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

// Include necessary files
include_once plugin_dir_path(__FILE__) . 'includes/enqueue.php';
include_once plugin_dir_path(__FILE__) . 'includes/shortcodes.php';
include_once plugin_dir_path(__FILE__) . 'includes/form-handlers.php';
include_once plugin_dir_path(__FILE__) . 'includes/admin.php';
include_once plugin_dir_path(__FILE__) . 'includes/db-setup.php';

// Register activation hook
register_activation_hook(__FILE__, 'kandara_create_registration_table');

?>