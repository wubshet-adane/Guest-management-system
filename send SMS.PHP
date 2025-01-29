<?php
require 'vendor/autoload.php';

use Twilio\Rest\Client;

function sendSMS($to, $message) {
    // Twilio credentials
    $account_sid = 'your_account_sid';
    $auth_token = 'your_auth_token';
    $twilio_number = 'your_twilio_number';

    // Initialize the Twilio client
    $client = new Client($account_sid, $auth_token);

    // Send SMS
    $client->messages->create(
        $to, // Recipient's phone number
        [
            'from' => $twilio_number, // Twilio phone number
            'body' => $message
        ]
    );
}

// Example usage
$to = '+1234567890'; // Recipient's phone number
$message = 'Hello, this is a test SMS from Twilio!';
sendSMS($to, $message);

echo "SMS sent successfully!";
?>
