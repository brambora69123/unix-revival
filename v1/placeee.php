<?php
$currentUrl = $_SERVER['REQUEST_URI'];

// Endpoint for '/v1/places/'
$keywordActiveSessionMembers = '/v1/places/';

// Endpoint for '/places/{placeid}/settings'
$keywordSettings = '/places/';

if (strpos($currentUrl, $keywordActiveSessionMembers) !== false) {
    // Handle '/v1/places/' endpoint
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

    $data = [
        "previousPageCursor" => null,
        "nextPageCursor" => null,
        "data" => []
    ];

    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
} elseif (strpos($currentUrl, $keywordSettings) !== false) {
    // Handle '/places/{placeid}/settings' endpoint
    preg_match('/\/places\/(\d+)\/settings/', $currentUrl, $matches);
    $placeid = $matches[1]; // Extract placeid from the URL

    // include_once configuration file
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

    // Prepare JSON response
    $data = [
        "placeid" => $placeid,
        "settings" => [
            // Define settings data here as needed
        ]
    ];

    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
} else {
    // Handle other cases or return a 404 not found
    header("HTTP/1.0 404 Not Found");
    echo json_encode(["error" => "Endpoint not found"]);
    exit();
}
?>
