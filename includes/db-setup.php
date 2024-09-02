<?php
function kandara_create_registration_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'kandara_registrations';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        first_name varchar(100) NOT NULL,
        last_name varchar(100) NOT NULL,
        id_no varchar(20) NOT NULL,
        email varchar(100) NOT NULL,
        phone varchar(20) NOT NULL,
        mpesa_phone varchar(20) DEFAULT '',
        runner_category varchar(50) NOT NULL,
        race varchar(50) NOT NULL,
        tshirt_size varchar(5) NOT NULL,
        pickup_point varchar(255) NOT NULL,
        gender varchar(10) NOT NULL,
        bib_no varchar(10) NOT NULL,
        email_updates tinyint(1) DEFAULT 0,
        whatsapp_updates tinyint(1) DEFAULT 0,
        terms_conditions tinyint(1) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    $table_name_volunteers = $wpdb->prefix . 'kandara_volunteers';

    $sql .= "CREATE TABLE $table_name_volunteers (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        first_name varchar(50) NOT NULL,
        email varchar(50) NOT NULL,
        phone varchar(20) NOT NULL,
        gender varchar(10) NOT NULL,
        volunteer_role varchar(50) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

add_action('after_switch_theme', 'kandara_create_tables');

function kandara_drop_tables() {
    global $wpdb;
    $registration_table_name = $wpdb->prefix . 'kandara_registrations';
    $volunteer_table_name = $wpdb->prefix . 'kandara_volunteers';

    $wpdb->query("DROP TABLE IF EXISTS $registration_table_name");
    $wpdb->query("DROP TABLE IF EXISTS $volunteer_table_name");
}

add_action('switch_theme', 'kandara_drop_tables');
?>
