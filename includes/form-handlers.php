<?php
function kandara_process_form() {
    if (!isset($_POST['kandara-registration-nonce']) || !wp_verify_nonce($_POST['kandara-registration-nonce'], 'kandara-registration-form')) {
        wp_die('Nonce verification failed.');
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'kandara_registrations';

    $data = array(
        'first_name' => sanitize_text_field($_POST['first_name']),
        'last_name' => sanitize_text_field($_POST['last_name']),
        'id_no' => sanitize_text_field($_POST['id_no']),
        'email' => sanitize_email($_POST['email']),
        'phone' => sanitize_text_field($_POST['phone']),
        'mpesa_phone' => sanitize_text_field($_POST['mpesa_phone']),
        'runner_category' => sanitize_text_field($_POST['runner_category']),
        'race' => sanitize_text_field($_POST['race']),
        'tshirt_size' => sanitize_text_field($_POST['tshirt_size']),
        'pickup_point' => sanitize_text_field($_POST['pickup_point']),
        'gender' => sanitize_text_field($_POST['gender']),
        'email_updates' => isset($_POST['email_updates']) ? 1 : 0,
        'whatsapp_updates' => isset($_POST['whatsapp_updates']) ? 1 : 0,
        'terms_conditions' => isset($_POST['terms_conditions']) ? 1 : 0,
    );

    $wpdb->insert($table_name, $data);

    echo '<script>';
    echo 'alert("Your registration has been successfully submitted.Thank You !!🥳🎉");';
    echo 'window.location.href = "' . esc_url(home_url('/register-now')) . '";';
    echo '</script>';    
    
    exit;
}
add_action('admin_post_nopriv_kandara_process_form', 'kandara_process_form');
add_action('admin_post_kandara_process_form', 'kandara_process_form');

function kandara_process_volunteer_form() {
    if (!isset($_POST['kandara-volunteer-nonce']) || !wp_verify_nonce($_POST['kandara-volunteer-nonce'], 'kandara-volunteer-form')) {
        wp_die('Nonce verification failed.');
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'kandara_volunteers';

    $data = array(
        'first_name' => sanitize_text_field($_POST['first_name_volunteer']),
        'email' => sanitize_email($_POST['email_volunteer']),
        'phone' => sanitize_text_field($_POST['phone_volunteer']),
        'gender' => sanitize_text_field($_POST['gender_volunteer']),
        'volunteer_role' => sanitize_text_field($_POST['volunteer_role']),
    );

    $wpdb->insert($table_name, $data);

        echo '<script>';
        echo 'alert("Your have been successfully registered as a volunteer.Thank you!!.🥳🎉");';
        echo 'window.location.href = "' . esc_url(home_url('/volunteer')) . '";';
        echo '</script>';    
        
        exit;
}
add_action('admin_post_nopriv_kandara_process_volunteer_form', 'kandara_process_volunteer_form');
add_action('admin_post_kandara_process_volunteer_form', 'kandara_process_volunteer_form');

?>