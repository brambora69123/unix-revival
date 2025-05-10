<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/func.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/webhookstuff.php');

session_start();
$errors = array();
$ErrCount = 0;

// Register errors
$UserErrors = array();
$EmailErrors = array();
$PassErrors = array();

// Get current date
$date = date("Y-m-d");

// Epic rick keys
$rickKeys = array(
    "unixkey-HdAJd91hl4ia1a0j1",
    "unixkey-Jf9ak1lxam0lad1G",
    "unixkey-6ejNxz3DqU3Wjz11olrH09yV",
    "unixkey-zELGxenqXlrtXZHmB5fzSPr9",
    "unixkey-Vdm1oVgrKwSLH3Yxcp8ybGYL",
    "unixkey-oQaRkLJZpdp9WQJWJekVGJEg",
    "unixkey-FEETUWX5PwwR2iQiwuSCOatb",
    "unixkey-OL8GkYcR1DUaAR6fyu8tJTpi",
    "unixkey-y0X65Sat2zAsZQYWw019XnGB",
    "unixkey-X9KgsmvAORUFR7ntW5WOeNtX",
    "unixkey-0jXxmFtGMwjL9VgGDBhuBNF4",
    "unixkey-hqhR6qQzjn32W963VpL7HPeq",
    "unixkey-a7wxRpdlh3J2H0KIw8l44Cik",
    "unixkey-AOKBltRrgJ4bigbtT6iLTFBs",
    "unixkey-mcJDANleBD5OXySASFtnwk4S",
    "unixkey-yQumj5hsO9VFPd83Q0S8eRRq",);

function sanitizeInput($input) {
    return htmlspecialchars(strip_tags($input));
}

switch(true) {
    case (isset($_POST['loginunx'])):
        $username = sanitizeInput($_POST['user']);
        $password = $_POST['pass'];

    $usernameIsEmpty = empty($username);
$passwordIsEmpty = empty($password);

$errors = [];
$ErrCount = 0;

$usernameIsEmpty = empty($username);
$passwordIsEmpty = empty($password);

if ($usernameIsEmpty && $passwordIsEmpty) {
    array_push($errors, "Username & Password empty.");
    $ErrCount++;
} else {
    if ($usernameIsEmpty) {
        array_push($errors, "Username box is empty.");
        $ErrCount++;
    } else {
        if (preg_match('/^[a-z0-9_]+$/i', $username) == 0) {
            array_push($errors, "Invalid characters in username.");
            $ErrCount++;
        }
    }

    if ($passwordIsEmpty) {
        array_push($errors, "Password box is empty.");
        $ErrCount++;
    }
}


        // SQL injection and XSS checks
        if (preg_match('/\b(union|select|insert|update|delete)\b/i', $username) ||
            preg_match('/<script\b[^>]*>(.*?)<\/script>/i', $username)) {
            array_push($errors, "Nice one.");
            $ErrCount++;
        }

        switch(true) {
            case (count($errors) == 0):
                $loggon = $MainDB->prepare("SELECT password, token FROM users WHERE name = :username");
                $loggon->execute([':username' => $username]);
                $results = $loggon->fetch(PDO::FETCH_ASSOC);
                $checkpsw = $results['password'] ?? null;
                $token = $results['token'] ?? null;

                switch(true) {
                    case (!empty($checkpsw)):
                        if (password_verify($password, $checkpsw)) {
                            $expiration_time = time() + 40 * 24 * 60 * 60;

                            setcookie(".ROBLOSECURITY", $token, $expiration_time, "/", $_SERVER['SERVER_NAME']);
                            setcookie("ROBLOSECURITY", $token, $expiration_time, "/", $_SERVER['SERVER_NAME']);
							setcookie("_ROBLOSECURITY", $token, $expiration_time, "/", $_SERVER['SERVER_NAME']);

                            header("Location: " . $baseUrl . "/home");
                            sendLog("User ".$username." logged in.");
                            die();
                        } else {
                            array_push($errors, "Username or password is incorrect.");
                        }
                        break;
                    default:
                        array_push($errors, "Username or password is incorrect.");
                        break;
                }
                break;
        }
        break;

    case (isset($_POST['registerunx'])):

        $username = sanitizeInput($_POST['username']);
        $password = $_POST['password'];
        $password2 = $_POST['confpass'];
        $token = random_tkn();
        $email = "test@test.com";  // Replace with actual email input in a real scenario
        $refer = sanitizeInput($_POST['key']);

        if (in_array($refer, $rickKeys)) {
            header("Location: https://www.youtube.com/watch?v=xvFZjo5PgG0&ab_channel=Duran", true, 302);
            exit();
        }

$UserErrors = [];
$PassErrors = [];
$ErrCount = 0;

$usernameIsEmpty = empty($username);
$passwordIsEmpty = empty($password);

if ($usernameIsEmpty && $passwordIsEmpty) {
    array_push($UserErrors, "Username & Password empty.");
    $ErrCount++;
} else {
    if ($usernameIsEmpty) {
        array_push($UserErrors, "Username box is empty.");
        $ErrCount++;
    } else {
        if (preg_match('/^[a-z0-9_]+$/i', $username) == 0) {
            array_push($UserErrors, "Your username cannot have invalid characters.");
            $ErrCount++;
        }
        if (strlen($username) > 20) {
            array_push($UserErrors, "Name cannot be longer than 20 characters.");
            $ErrCount++;
        }
    }

    if ($passwordIsEmpty) {
        array_push($PassErrors, "Password box is empty.");
        $ErrCount++;
    } else {
        if ($password !== $password2) {
            array_push($PassErrors, "Passwords don't match.");
            $ErrCount++;
        }
        if (strlen($password) > 75) {
            array_push($PassErrors, "Password cannot be longer than 75 characters.");
            $ErrCount++;
        }
    }
}

        if (empty($email)) {
            array_push($EmailErrors, "Email box is empty.");
            $ErrCount++;
        } else {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($EmailErrors, "Invalid email.");
                $ErrCount++;
            }
        }

        if (preg_match('/\b(union|select|insert|update|delete)\b/i', $username) ||
            preg_match('/<script\b[^>]*>(.*?)<\/script>/i', $username)) {
            array_push($errors, "Nice one.");
            $ErrCount++;
        }

        $UEC = $MainDB->prepare("SELECT * FROM users WHERE name = :username");
        $UEC->execute([':username' => $username]);
        $Row = $UEC->fetch(PDO::FETCH_ASSOC);
        switch(true){case ($Row):array_push($UserErrors, "User already exists.");$ErrCount = $ErrCount + 1;break;}

        $referstmt = $MainDB->prepare("SELECT * FROM regkeys WHERE elkey = :urmomfat");
        $referstmt->bindParam(":urmomfat", $refer, PDO::PARAM_STR);
        $referstmt->execute();
        $referkeyxdorsomething = $referstmt->fetch(PDO::FETCH_ASSOC);
        $q = $referstmt->rowCount();

        if ($q == 0 || $q < 1) {
            array_push($errors, "Registration key doesn't exist.");
            $ErrCount++;
        }

        $referstmt2 = $MainDB->prepare("SELECT used FROM regkeys WHERE elkey = :urmomfat");
        $referstmt2->bindParam(":urmomfat", $refer, PDO::PARAM_STR);
        $referstmt2->execute();
        $referkeyxdorsomething2 = $referstmt2->fetch(PDO::FETCH_ASSOC);

        $q4 = $MainDB->prepare("SELECT used FROM regkeys WHERE elkey=?");
        $q4->execute([$refer]);
        $used = $q4->fetchColumn();

        if ($used == 1 || $used > 1) {
            die("Your Registration key has been used.");
        }

        switch(true) {
            case ($ErrCount == 0):
                $stmte = $MainDB->prepare("UPDATE regkeys SET used = 1, usedby = :usa WHERE elkey = :urmomfat");
                $stmte->bindParam(":urmomfat", $refer, PDO::PARAM_STR);
                $stmte->bindParam(":usa", $username, PDO::PARAM_STR);
                $stmte->execute();
                $email = "test@test.com"; // Replace with actual email input in a real scenario
                $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
                $discordvercode = substr(bin2hex(random_bytes(128)), 0, 25);
                $InsertToDB = $MainDB->prepare("INSERT INTO `users` (`id`, `name`, `password`, `ticket`, `robux`, `email`, `status`, `membership`, `creationdate`, `token`, `friends`, `admin`, `vercode`, `discordverified`) VALUES (NULL, ?, ?, '10', '10', ?, 'UNIX.', 'None', ?, ?, 0, 0, ?, 0)")->execute([$username, $hashedpassword, $email, $date, $token, $discordvercode]);
                setcookie("ROBLOSECURITY", $token, time() + 9900, "/", $_SERVER['SERVER_NAME']);
                setcookie(".ROBLOSECURITY", $token, time() + 9900, "/", $_SERVER['SERVER_NAME']);
				setcookie("_ROBLOSECURITY", $token, $expiration_time, "/", $_SERVER['SERVER_NAME']);

                sendLog("New account named ".$username." created! (key used: ". $refer .")");
                header("Location: ". $baseUrl ."/home");
                
                die();
                break;
        }
        break;
}
?>
