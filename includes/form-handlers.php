<?php
function kandara_process_form() {
    // Verify the nonce for security
    if (!isset($_POST['kandara-registration-nonce']) || !wp_verify_nonce($_POST['kandara-registration-nonce'], 'kandara-registration-form')) {
        wp_send_json_error('Nonce verification failed.');
        wp_die();
    }

    include_once plugin_dir_path(__FILE__) . '../payment.php';
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'kandara_registrations';

    // Sanitize and prepare the data for insertion
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

    // Insert the data without Bib No
    $inserted = $wpdb->insert($table_name, $data);

    // Check if the insertion was successful
    if ($inserted === false) {
        wp_send_json_error('Error processing the registration. Please try again.');
        wp_die();
    }

    // Get the last inserted ID (primary key)
    $registration_id = $wpdb->insert_id;

    // Define Bib No prefix based on the race
    $bib_prefix = '';
    $race = sanitize_text_field($_POST['race']);

    switch ($race) {
        case '5km':
            $bib_prefix = '05';
            break;
        case '10km':
            $bib_prefix = '10';
            break;
        case '21km':
            $bib_prefix = '21';
            break;
        default:
            $bib_prefix = 'XX'; // Fallback for unexpected values
            break;
    }

    // Generate Bib No by combining the prefix with the registration ID
    $bib_no = $bib_prefix . str_pad($registration_id, 4, '0', STR_PAD_LEFT);

    // Update the row with the generated Bib No
    $updated = $wpdb->update(
        $table_name,
        array('bib_no' => $bib_no),
        array('id' => $registration_id)
    );

    // Check if the update was successful
    if ($updated === false) {
        wp_send_json_error('Error assigning the Bib number. Please contact support.');
        wp_die();
    }

    // Process payment
    $payment_result = process_kandara_payment();

    // Check if the payment was successful
    if (is_wp_error($payment_result)) {
        wp_send_json_error('Registration was successful, but there was an issue with the payment: ' . $payment_result->get_error_message());
        wp_die();
    }

    // Send SMS notification after payment is successful
    $message = "Thank you " . $data['first_name'] . " for registering for the Kandara Run! Your Bib No is " . $bib_no;
    $recipients = $data['phone'];

    $sms_result = send_sms_notification($recipients, $message);

    // Check if the SMS sending was successful
    if (!$sms_result) {
        wp_send_json_error('Registration was successful, but there was an issue sending the SMS.');
        wp_die();
    }
    // Return success message
    wp_send_json_success('Registration and payment successful! Check your SMS for your Bib number.');
    wp_die(); // Ensure that the script terminates correctly

}

add_action('wp_ajax_nopriv_kandara_process_form', 'kandara_process_form');
add_action('wp_ajax_kandara_process_form', 'kandara_process_form');

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

use AfricasTalking\SDK\AfricasTalking;

function kandara_send_sms($recipients, $message) {
    // Africa's Talking credentials
    $username = 'kandara'; 
    $apiKey   = '9bbf59ba82da56ce9a9d58b44100a6e2190b4c7e65bc5ff87b5f0d3b78a6686d';

    // Initialize SDK
    $AT       = new AfricasTalking($username, $apiKey);

    // Get the SMS service
    $sms      = $AT->sms();

    try {
        // Send SMS
        $result = $sms->send([
            'to'      => $recipients,
            'message' => $message,
            'from'    => 'KANDARARUN'
        ]);

        return $result;

    } catch (Exception $e) {
        return "Error: ".$e->getMessage();
    }
}


?>