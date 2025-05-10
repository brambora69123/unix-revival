<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuserbutnobanredirect.php');
switch (true) {
    case ($RBXTICKET == null):
        header('Location: ' . $baseUrl . '/', true, 302);
        die();
        break;
}
$currentTime = date(time());

$stmt = $MainDB->prepare("SELECT termtype, bannedAt, banEndsAt FROM users WHERE id = :uid");
$stmt->bindParam(":uid", $id, PDO::PARAM_INT);
$stmt->execute();
$tShit = $stmt->fetch(PDO::FETCH_ASSOC);
$gay = $tShit["banEndsAt"];
$termType = $tShit["termtype"];

if ($termType == null) {
    echo "<script>alert('Your account is not banned.'); document.location = '$baseUrl'</script>";
    die();
} else if ($termType == "terminated") {
    echo "<script>alert('You are permanently banned from UNIX. This operation is forbidden.'); document.location = '$baseUrl'</script>";
    die();
}

if ($gay <= $currentTime) {
    $stmt = $MainDB->prepare("UPDATE users SET termtype = null WHERE id = :uid");
    $stmt->bindParam(":uid", $id, PDO::PARAM_INT);
    $stmt->execute();
    echo "<script>alert('Account reactivated successfully! Press OK to return to the home page.'); document.location = '$baseUrl'</script>";
    die();
} else {
    echo "<script>alert('Your ban has not expired yet.'); document.location = '$baseUrl'</script>";
    die();
}
?>