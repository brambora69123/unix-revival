<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');

/*$webhookurl = "https://discord.com/api/webhooks/1192190014688338050/Co5drj4wcnZfGdzuDmvlq35uRPh0qiLLBo6oegXY8o4F-SjpecIPL8lkJFZOoSdyMkor";

$timestamp = date("c", strtotime("now"));

$json_data = json_encode([
    // Message
    "content" => "testtttt",

    // Avatar URL.
    // Uncoment to replace image set in webhook
    //"avatar_url" => "https://ru.gravatar.com/userimage/28503754/1168e2bddca84fec2a63addb348c571d.jpg?size=512",

    // Text-to-speech
    "tts" => false,

    // File upload
    // "file" => "",

    // Embeds Array
    

], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );


$ch = curl_init( $webhookurl );
curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
curl_setopt( $ch, CURLOPT_POST, 1);
curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt( $ch, CURLOPT_HEADER, 0);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec( $ch );
// If you need to debug, or find out why you can't send message uncomment line below, and execute script.
echo $response;
curl_close( $ch );*/

// URL to which you want to send the POST request
$url = 'https://discord.com/api/webhooks/1192190014688338050/Co5drj4wcnZfGdzuDmvlq35uRPh0qiLLBo6oegXY8o4F-SjpecIPL8lkJFZOoSdyMkor';

// Data to be sent in the POST request
$data = array(
    'contents' => 'el sexo'
);

// Initialize cURL session
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute cURL session and get the response
$response = curl_exec($ch);

// Close cURL session
curl_close($ch);

// Process the response as needed
echo $response;

?>