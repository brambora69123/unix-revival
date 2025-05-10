<?php

function sendLog($msg, $type = "info", $render = null){
    $webhook_url = "https://discord.com/api/webhooks/1251643398235095182/vG6VCxrKRhDfBwqUCkJ4JyFYUy5hnIZZS02R9Gef431d7hHrzVxNBdsKg5s_PqWPl8oX";

    $embed = [
        "title" => "Arbiter Log",
        "description" => $msg,
        "color" => hexdec("800080")
    ];

    if ($type == "error") {
        $embed["color"] = hexdec("FF0000");
        $embed["title"] = "Arbiter Log: Error";
    }

    if ($type == "render") {
        $embed["color"] = hexdec("FFA500");
        $embed["title"] = "Arbiter Log: Render";
    }

    if ($type == "newjob") {
        $embed["color"] = hexdec("00FF00");
        $embed["title"] = "Arbiter Log: New Job";
    }

    if ($type == "jobclosed") {
        $embed["color"] = hexdec("FF0000");
        $embed["title"] = "Arbiter Log: Job Closed";
    }

    if ($type == "render") {
        $embed["image"] = ["url" => $render];
    }

    $payload = [
        "embeds" => [$embed]
    ];

    $json_payload = json_encode($payload);

    $headers = array('Content-Type: application/json');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $webhook_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);

    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;
}

?>
