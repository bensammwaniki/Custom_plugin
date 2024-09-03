<?php
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to generate access token
function getMpesaAccessToken() {
    // Get the credentials from environment variables or hardcode them here for testing
    $consumerKey = "rf3PCcVxTu8G9sw8LVqX5u51EZ4UmFohH3GsvBbxOO9wLNBn";
    $consumerSecret = "ZdxujcOhvWVAAPggPgcZjzCGpResL8eEs15Dfteq2Jsx9pLHJ5183trSlgzPL4XJ";
    
    // Define the API endpoint
    $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

    try {
        // Initialize the Guzzle HTTP client
        $client = new Client();
        
        // Prepare the request with Basic Authentication
        $response = $client->request('GET', $url, [
            'auth' => [$consumerKey, $consumerSecret] // Basic Auth: base64(consumerKey:consumerSecret)
        ]);
        
        // Decode the JSON response
        $body = json_decode($response->getBody(), true);
        
        // Return the access token if successful
        if (isset($body['access_token'])) {
            return $body['access_token'];
        } else {
            error_log('Error: Access token not found in the response.');
            return false;
        }

    } catch (RequestException $e) {
        // Log the error message
        error_log('RequestException: ' . $e->getMessage());
        
        // If there's a response, log the status and body
        if ($e->hasResponse()) {
            $response = $e->getResponse();
            error_log('Response Status: ' . $response->getStatusCode());
            error_log('Response Body: ' . $response->getBody());
        }
        
        return false;
    } catch (Exception $e) {
        // Log any other exceptions
        error_log('Exception: ' . $e->getMessage());
        return false;
    }
}

// M-Pesa STK Push Payment Function
function process_kandara_payment() {
    error_log('process_kandara_payment() called');

    if (isset($_POST['kandara_register'])) {
        // Verify nonce for security
        if (!isset($_POST['kandara-registration-nonce']) || !wp_verify_nonce($_POST['kandara-registration-nonce'], 'kandara-registration-form')) {
            wp_send_json_error('Nonce verification failed.');
            return;
        }

        // Store the form data in session
        $_SESSION['form_data'] = $_POST;

        // Get M-Pesa Access Token
        $accessToken = getMpesaAccessToken();
        if (!$accessToken) {
            wp_send_json_error('Unable to retrieve M-PESA access token.');
            return;
        }

        // Safaricom API credentials
        $businessShortCode = '174379'; // Business Shortcode
        $lipaNaMpesaOnlinePasskey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'; // Lipa na M-PESA Online Passkey
        $timestamp = date('YmdHis');
        $password = base64_encode($businessShortCode . $lipaNaMpesaOnlinePasskey . $timestamp);

        // M-PESA endpoint
        $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

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

        // Initialize the Guzzle HTTP client
        $client = new Client();

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
?>
