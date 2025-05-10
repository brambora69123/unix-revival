<?php


    require ($_SERVER['DOCUMENT_ROOT'].'/config.php');
    header('Content-Type: application/json');

    $receiptid = filter_var($_GET['receipt'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);

    $receiptquery = $MainDB->prepare("SELECT * FROM `devproduct` WHERE `reciept` = :receipt");
    $receiptquery->execute(['receipt' => $receiptid]);
    $receipt = $receiptquery->fetch();

    if (is_array($receipt)) {
        $data = array(
            'playerId' => $receipt['plrid'],
            'placeId' => $receipt['pid'],
            'isValid' => true,
            'productId' => $receipt['productId']
        );
        die(json_encode($data));
    } else {
        $data = array('playerId' =>  $receipt['plrid'], 'placeId' => $receipt['pid'], 'isValid' => true, 'productId' => $receipt['productId']);
        die(json_encode($data));
    }

?>
