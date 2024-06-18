<?php
function kandara_create_registration_table() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'kandara_registrations';

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        first_name varchar(50) NOT NULL,
        last_name varchar(50) NOT NULL,
        id_no varchar(20) NOT NULL,
        email varchar(50) NOT NULL,
        phone varchar(20) NOT NULL,
        mpesa_phone varchar(20),
        runner_category varchar(20) NOT NULL,
        race varchar(10) NOT NULL,
        tshirt_size varchar(5) NOT NULL,
        pickup_point varchar(100) NOT NULL,
        gender varchar(10) NOT NULL,
        email_updates tinyint(1),
        whatsapp_updates tinyint(1),
        terms_conditions tinyint(1),
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_switch_theme', 'kandara_create_registration_table');

function kandara_create_volunteer_table() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'kandara_volunteers';

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        first_name varchar(50) NOT NULL,
        last_name varchar(50) NOT NULL,
        id_no varchar(20) NOT NULL,
        email varchar(50) NOT NULL,
        phone varchar(20) NOT NULL,
        gender varchar(10) NOT NULL,
        volunteer_role varchar(50) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_switch_theme', 'kandara_create_volunteer_table');
