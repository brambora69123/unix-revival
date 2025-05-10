<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
header('Content-Type: application/json');
switch (true) {
  case ($RBXTICKET == null):
    die(header("Location: " . $baseUrl . "/"));
    break;
}

if (isset($_GET["status"])) {
    $newstatus = nx(filter_var($_GET["status"]));


   if (empty($newstatus)) {
        $errorResponse = ['error' => 'Status cannot be empty.'];
        die(json_encode($errorResponse));
    }
    $stmt = $MainDB->prepare("UPDATE users SET status = :ass WHERE id = :ass2electricboogaloo");
    $stmt->bindParam(":ass", $newstatus, PDO::PARAM_STR);
    $stmt->bindParam(":ass2electricboogaloo", $id, PDO::PARAM_STR);
    $stmt->execute();

 
} else {
    $errorResponse = ['error' => 'Status not found in the request.'];
    die(json_encode($errorResponse));
}
?>
