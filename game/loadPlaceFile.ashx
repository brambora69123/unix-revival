<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/func.php');
include($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
header("content-type: text/plain");
  function signin($data) {
    include($_SERVER['DOCUMENT_ROOT'] . '/config.php');
    $PrivKey = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/game/0b1BTQn2BgFq.pem");
    openssl_sign($data, $signature, $PrivKey, OPENSSL_ALGO_SHA1);
    switch($SignType){
      case "2":
        echo sprintf("%%%s%%%s", base64_encode($signature), $data);
        break;
      default:
        echo sprintf("--rbxsig%%%s%%%s", base64_encode($signature), $data);
        break;
    }
  }
  
$pid = (int)($_GET['PlaceId'] ?? die(json_encode(["message" => "Cannot process request at this time."])));
	switch(true){
		case ($pid):
			$PlaceFetch = $MainDB->prepare("SELECT * FROM asset WHERE id = :pid AND itemtype = 'place'");
			$PlaceFetch->execute([":pid" => $pid]);
			$Results = $PlaceFetch->fetch(PDO::FETCH_ASSOC);
			switch(true){
				case($Results):
					switch (file_exists($_SERVER['DOCUMENT_ROOT'] . "/asset/". $pid)){
						 case true:
							switch(true){
								case ($Results['approved'] == "1"):
									signin("\r\ngame:Load('". $baseUrl ."/asset/?id=". $pid ."')");
									break;
								default:
									switch(true){case($RBXTICKET == null):die(signin("\r\nprint('Asset visibility is restricted.')"));break;case($RBXTICKET !== null):switch(true){case($id == $Results['creatorid']):signin("\r\ngame:Load('". $baseUrl ."/asset/?id=". $pid ."')");break;default:die(signin('\r\nprint("Unable to verify asset owner.")'));break;}break;}
									break;
							}
							break;
						 default:
							signin('\r\nprint("Cannot retrieve game at this time.")');
							break;
					}
					break;
				default:
					signin('\r\nprint("Unable to fetch game.")');
					break;
			}
			break;
		default:
			signin('\r\nprint("Cannot process request at this time. Try again later.")');
			break;
	}
//to note here:
//there's a few issues with just going like signin(); instead of 
//signin('
//');
//so studio needs to load scripts, and to do this, the first line has to be ONLY the signature
//and then comes the actual script.
?>