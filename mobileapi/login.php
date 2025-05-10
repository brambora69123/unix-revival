<?php 
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/func.php');

session_start();
$errors = array();
//date
$date = date("Y-m-d");

switch(true){
	case (isset($_POST)):
		$rawPost = file_get_contents('php://input');
		$FilterOne = explode('&', $rawPost);
		$username = str_replace("username=", "", $FilterOne[0]);
		$password = str_replace("password=", "", $FilterOne[1]);
		
		switch(true){case (empty($username)):array_push($errors, "Username box is empty.");break;}
		switch(true){case (empty($username)):array_push($errors, "Password box is empty.");break;}
		switch(true){case (preg_match('/^[a-z0-9_]+$/i', $username) == 0):array_push($errors, "User does not exist.");break;}
		switch(true){case (preg_match('/^[a-z0-9_]+$/i', $password) == 0):array_push($errors, "User does not exist.");break;}
		
		switch(true){
			case (count($errors) == 0):
				$loggon = $MainDB->prepare("SELECT password, robux, ticket, id, token, termtype FROM users WHERE name = :username");
				$loggon->execute([':username' => $username]);
				$results = $loggon->fetch(PDO::FETCH_ASSOC);
				$checkpsw = ($results['password'] ?? null);
				$token = ($results['token'] ?? null);
				$robux = ($results['robux'] ?? null);
				$tix = ($results['ticket'] ?? null);
				$userid = ($results['id'] ?? null);
				$termtype = ($results['termtype'] ?? null);
			
				switch (true){
					case ($termtype !== null):
						die(json_encode(["message" => "Terminated"]));
						break;
				}
			
				switch(true){
					case (!empty($checkpsw)):
						switch(true){
							case (password_verify($password,$checkpsw)):
								setcookie(".ROBLOSECURITY", $token, time()-9900, "/", "unixfr.xyz");
								setcookie("_ROBLOSECURITY", $token, time()-9900, "/", "unixfr.xyz");
								setcookie("ROBLOSECURITY", $token, time()+9900, "/", "unixfr.xyz");
								$LoginReply = ["Status" => "OK", "UserInfo" => ["UserName" => $username, "RobuxBalance" => $robux, "TicketsBalance" => $tix, "IsAnyBuildersClubMember" => "false", "ThumbnailUrl" => $baseUrl . "/Tools/Asset.ashx?id=" . $userid . "&request=avatar", "UserID" => $userid]];
								die(json_encode($LoginReply, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
								break;
							default:
								die(json_encode(["message" => "Wrong Information."]));
								break;
						}
						break;
					default:
						die(json_encode(["message" => "Wrong Information."]));
						break;
				}
				break;
			default:
				die(json_encode(["message" => "Wrong Information."]));
				break;
		}
		break;
}
?>