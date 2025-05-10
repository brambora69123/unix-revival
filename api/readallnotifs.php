<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');

try {
    $MainDB->beginTransaction();

    $update = $MainDB->prepare("UPDATE notification SET unread = 0 WHERE userId = :id");
    $update->bindParam(":id", $id, PDO::PARAM_INT);
    $update->execute();

    $update2 = $MainDB->prepare("UPDATE friend_requests SET unread = 0 WHERE user2 = :id");
    $update2->bindParam(":id", $id, PDO::PARAM_INT);
    $update2->execute();

    $MainDB->commit();
    $response = array('status' => 'success', 'message' => 'Notifications marked as read');
} catch (PDOException $e) {
    $MainDB->rollBack();
    $response = array('status' => 'error', 'message' => 'Failed to update notifications: ' . $e->getMessage());
}

echo json_encode($response);
?>
