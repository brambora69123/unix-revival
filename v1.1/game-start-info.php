<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
header('Content-Type: application/json');

$gameAvatarType = "PlayerChoice";

$universeId = isset($_GET['universeId']) ? $_GET['universeId'] : null;

if ($universeId !== null) {
    $stmt = $MainDB->prepare("SELECT avatartype, gameid FROM asset WHERE id = :universeId");
    $stmt->bindParam(':universeId', $universeId, PDO::PARAM_INT);
    
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $avatartype = $result['avatartype'];
        $placeid = $result['gameid'];
        
        if ($avatartype === 'R6') {
            $gameAvatarType = "MorphToR6";
        } elseif ($avatartype === 'R15') {
            $gameAvatarType = "MorphToR15";
        } elseif ($avatartype === 'Choice' || $placeid == 0) {
            $gameAvatarType = "PlayerChoice";
        }
    }
}
echo json_encode(array(
    "gameAvatarType" => $gameAvatarType
), JSON_UNESCAPED_SLASHES);
?>
