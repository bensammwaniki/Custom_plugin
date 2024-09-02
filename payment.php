<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include WordPress functions
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
require_once('vendor/autoload.php'); // if you are using Composer for the Guzzle HTTP client

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

function process_kandara_payment() {
    error_log('process_kandara_payment() called');
    // Handle the form submission
    if (isset($_POST['kandara_register'])) {
        // Verify nonce for security
        if (!isset($_POST['kandara-registration-nonce']) || !wp_verify_nonce($_POST['kandara-registration-nonce'], 'kandara-registration-form')) {
            wp_send_json_error('Nonce verification failed.');
            return;
        }

        // Store the form data in session
        $_SESSION['form_data'] = $_POST;

        // Initialize the Guzzle HTTP client
        $client = new Client();

        // Safaricom API credentials
        $businessShortCode = '174379'; // Business Shortcode
        $lipaNaMpesaOnlinePasskey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'; // Lipa na M-PESA Online Passkey
        $timestamp = date('YmdHis');
        $password = base64_encode($businessShortCode . $lipaNaMpesaOnlinePasskey . $timestamp);

        // M-PESA endpoint
        $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

        // Access token
        $accessToken = getMpesaAccessToken(); // Implement this function to get access token
        if (!$accessToken) {
            wp_send_json_error('Unable to retrieve M-PESA access token.');
            return;
        }

        // Prepare the request body
        $body = [
            'BusinessShortCode' => $businessShortCode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => 1, // The amount to be charged
            'PartyA' => '254743491012', // The phone number sending money
            'PartyB' => $businessShortCode,
            'PhoneNumber' => '254743491012', // The phone number to receive the STK push
            'CallBackURL' => 'https://mydomain.com/path', // Your callback URL
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

            // Log the response for debugging
            error_log('M-PESA Response: ' . print_r($responseBody, true));

            if ($responseBody['ResponseCode'] == '0') {
                wp_send_json_success('Payment initiated. Check your phone for the M-PESA prompt.');
            } else {
                error_log('Error in M-PESA Response: ' . $responseBody['ResponseDescription']);
                wp_send_json_error('Error initiating payment: ' . $responseBody['ResponseDescription']);
            }
        } catch (RequestException $e) {
            error_log('RequestException: ' . $e->getMessage());
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                error_log('Response Status: ' . $response->getStatusCode());
                error_log('Response Body: ' . $response->getBody());
            }
            wp_send_json_error('Error: ' . $e->getMessage());
        } catch (Exception $e) {
            error_log('Exception: ' . $e->getMessage());
            wp_send_json_error('Error: ' . $e->getMessage());
        }
    }
}

// Function to get the M-PESA access token
function getMpesaAccessToken() {
    $client = new Client();

    try {
        $response = $client->request('GET', 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials', [
            'auth' => ['rf3PCcVxTu8G9sw8LVqX5u51EZ4UmFohH3GsvBbxOO9wLNBn', 'ZdxujcOhvWVAAPggPgcZjzCGpResL8eEs15Dfteq2Jsx9pLHJ5183trSlgzPL4XJ']
        ]);

        $responseBody = json_decode($response->getBody(), true);
        return $responseBody['access_token'];

    } catch (RequestException $e) {
        error_log('RequestException in getMpesaAccessToken: ' . $e->getMessage());
        if ($e->hasResponse()) {
            $response = $e->getResponse();
            error_log('Response Status in getMpesaAccessToken: ' . $response->getStatusCode());
            error_log('Response Body in getMpesaAccessToken: ' . $response->getBody());
        }
        return null;
    } catch (Exception $e) {
        error_log('Exception in getMpesaAccessToken: ' . $e->getMessage());
        return null;
    }
}
?>
