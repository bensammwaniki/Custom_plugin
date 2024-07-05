<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include WordPress functions
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
require_once('vendor/autoload.php'); // if you are using Composer for the Guzzle HTTP client

use GuzzleHttp\Client;

// Handle the form submission
if (isset($_POST['kandara_register'])) {
    // Verify nonce for security
    if (!isset($_POST['kandara-registration-nonce']) || !wp_verify_nonce($_POST['kandara-registration-nonce'], 'kandara-registration-form')) {
        wp_die('Nonce verification failed.');
    }

    // Store the form data in session
    $_SESSION['form_data'] = $_POST;

    // Initialize the Guzzle HTTP client
    $client = new Client();

    // Safaricom API credentials
    $businessShortCode = '174379'; //Business Shortcode
    $lipaNaMpesaOnlinePasskey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'; //Lipa na M-PESA Online Passkey
    $timestamp = date('YmdHis');
    $password = base64_encode($businessShortCode . $lipaNaMpesaOnlinePasskey . $timestamp);

    // M-PESA endpoint
    $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

    // Access token
    $accessToken = getMpesaAccessToken(); // Implement this function to get access token

    // Prepare the request body
    $body = [
        'BusinessShortCode' => $businessShortCode,
        'Password' => $password,
        'Timestamp' => $timestamp,
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => 1, // The amount to be charged
        'PartyA' => '254743491012', // The phone number sending money
        'PartyB' => $businessShortCode,
        'PhoneNumber' => '254708374149', // The phone number to receive the STK push
        'CallBackURL' => 'https://revamp.kandaramarathon.org/callback', // Your callback URL
        'AccountReference' => 'Test',
        'TransactionDesc' => 'Test'
    ];

    // Send the request
    try {
        $response = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($body)
        ]);

        $responseBody = json_decode($response->getBody(), true);

        if ($responseBody['ResponseCode'] == '0') {
            echo "<script>alert('Payment initiated. Check your phone for the M-PESA prompt.');</script>";
        } else {
            echo "<script>alert('Error initiating payment: " . $responseBody['ResponseDescription'] . "');</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}

// Function to get the M-PESA access token
function getMpesaAccessToken() {
    $client = new Client();

    $response = $client->request('GET', 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials', [
        'auth' => ['DAPZt9Mb82CAt7NPOfd135NA079Io3PO', 'OmyrpSyyKGfoHVYt']
    ]);

    $responseBody = json_decode($response->getBody(), true);

    return $responseBody['access_token'];
}
?>
