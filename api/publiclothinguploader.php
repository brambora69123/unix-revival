<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/webhookstuff.php');

$RBXTICKET = $_COOKIE['ROBLOSECURITY'] ?? null;
if ($RBXTICKET == null) {
    die(header("Location: " . $baseUrl . "/"));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name'], $_FILES["decalfile"], $_POST["clothingtype"], $_POST["price"])) {
        $ename = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
        $clothingType = htmlspecialchars($_POST["clothingtype"], ENT_QUOTES, 'UTF-8');
        $price = floatval($_POST["price"]);

        if ($price <= 0) {
            die("Price must be greater than 0.");
        }
		$GetInfoee = $MainDB->prepare("SELECT id, name, ticket, robux, status, about, nextrobuxgive, termtype, treason, toi, tnote, displayname, tdate, bannedAt, banEndsAt, email, emailverified, membership, friends, creationdate, phone, admin, theme, backgroundEnabled, lastGameUpload FROM users WHERE token = :token");
    $GetInfoee->execute([':token' => $RBXTICKET]);
    $Infoee = $GetInfoee->fetch(PDO::FETCH_ASSOC);

    
        $time = date("d/m/Y");
        $currenttime = time();
        if ($lastGameUpload + 43200 > $currenttime && $admin !== 1) {
            die("Please wait 12 hours before uploading a new game.");
        }
		
        $uploadedDecal = $_FILES["decalfile"]["tmp_name"];
        $decalFileType = strtolower(pathinfo($_FILES["decalfile"]["name"], PATHINFO_EXTENSION));

        $allowedImageTypes = array('png', 'jpeg', 'jpg');
        if (!in_array($decalFileType, $allowedImageTypes)) {
            die("Invalid decal file format. Allowed formats: PNG, JPEG, JPG");
        }

        $time = date("Y-m-d H:i:s");
        $query = "INSERT INTO asset (name, approved, creatorname, creatorid, gameid, updatedon, address, createdon, public, itemtype, maxPlayers)
                  VALUES (:name, 0, :cname, :cid, 'can we do this later ples', :updatedon, '191.96.208.35', :createdon, 0, 'Decal', 20)";
        $creategamestmt = $MainDB->prepare($query);

        $creategamestmt->bindParam(":name", $ename, PDO::PARAM_STR);
        $creategamestmt->bindParam(":cname", $name, PDO::PARAM_STR);
        $creategamestmt->bindParam(":cid", $id, PDO::PARAM_INT);
        $creategamestmt->bindParam(":updatedon", $time, PDO::PARAM_STR);
        $creategamestmt->bindParam(":createdon", $time, PDO::PARAM_STR);

        if (!$creategamestmt->execute()) {
            die("Failed to insert Decal into database.");
        }

        $decalId = $MainDB->lastInsertId();

        $decalDestination = $_SERVER["DOCUMENT_ROOT"] . "/asset/" . $decalId;
        if (!move_uploaded_file($uploadedDecal, $decalDestination)) {
            die("Failed to move uploaded decal image file.");
        }

        $xmlContent = generateClothingXML($decalId, "http://unixfr.xyz", $clothingType);

        $xmlName = $ename; 
        $query = "INSERT INTO asset (name, approved, creatorname, creatorid, gameid, updatedon, address, createdon, public, itemtype, maxPlayers, rsprice, free)
                  VALUES (:xmlname, 1, :cname, :cid, 'can we do this later ples', :updatedon, '191.96.208.35', :createdon, 0, :clothingType, 20, :robux, 0)";
        $creategamestmt = $MainDB->prepare($query);

        $creategamestmt->bindParam(":xmlname", $xmlName, PDO::PARAM_STR);
        $creategamestmt->bindParam(":cname", $name, PDO::PARAM_STR);
        $creategamestmt->bindParam(":cid", $id, PDO::PARAM_INT);
        $creategamestmt->bindParam(":updatedon", $time, PDO::PARAM_STR);
        $creategamestmt->bindParam(":createdon", $time, PDO::PARAM_STR);
        $creategamestmt->bindParam(":clothingType", $clothingType, PDO::PARAM_STR);
        $creategamestmt->bindParam(":robux", $price, PDO::PARAM_STR);

        if (!$creategamestmt->execute()) {
            die("Failed to insert Clothing XML into database.");
        }

        $xmlId = $MainDB->lastInsertId();

        $xmlDestination = $_SERVER["DOCUMENT_ROOT"] . "/asset/" . $xmlId;
        if (!file_put_contents($xmlDestination, $xmlContent)) {
            die("Failed to save XML content to file.");
        }


          

        switch ($clothingType) {
            case 'TShirt':
                $elurl = "https://unixfr.xyz/soapy/unix/Roblox/decal?id=" . $xmlId;
                break;
            case 'Shirt':
            case 'Pants':
                $elurl = "https://unixfr.xyz/soapy/unix/Roblox/clothingrender?id=" . $xmlId;
                break;
            default:
                die("Invalid clothing type.");
                break;
        }
		
		$giveItem = $MainDB->prepare("INSERT INTO bought (boughtby, boughtid, boughtname, itemtype, boughtfrom)
                                                VALUES (:buyer, :itemid, :itemname, :itemtype, :creator)");
                  $giveItem->bindParam(":buyer", $id, PDO::PARAM_INT);
                  $giveItem->bindParam(":itemid", $xmlId, PDO::PARAM_INT);
                  $giveItem->bindParam(":itemname", $xmlName, PDO::PARAM_STR);
                  $giveItem->bindParam(":itemtype", $clothingType, PDO::PARAM_STR);
                  $giveItem->bindParam(":creator", $id, PDO::PARAM_INT);
                  $giveItem->execute();

        $response = file_get_contents($elurl);
        if ($response === false) {
            die("Failed to fetch content from URL: " . $elurl);
        }

        $stmt = $MainDB->prepare("UPDATE users SET lastGameUpload = :curtime WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":curtime", $time, PDO::PARAM_STR);
        $stmt->execute();

        sendLog("A user generated clothing asset has been uploaded!");
    } else {
        die("Required data not set.");
    }
} else {
    die("Invalid request method.");
}
function generateClothingXML($decalId, $baseUrl, $clothingType) {
    switch ($clothingType) {
        case 'Shirt':
            $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>
<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://www.roblox.com/roblox.xsd" version="4">
    <External>null</External>
    <External>nil</External>
    <Item class="Shirt" referent="RBX188C484E5E2046CCAF2EC0D5BFBC7222">
        <Properties>
            <string name="Name">Clothing</string>
            <Content name="ShirtTemplate"><url>' . $baseUrl . '/asset/?id=' . $decalId . '</url></Content>
        </Properties>
    </Item>
</roblox>';
            break;
        case 'TShirt':
            $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>
<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://www.roblox.com/roblox.xsd" version="4">
    <External>null</External>
    <External>nil</External>
    <Item class="ShirtGraphic" referent="RBX0">
        <Properties>
            <Content name="Graphic">
                <url>' . $baseUrl . '/asset/?id=' . $decalId . '</url>
            </Content>
            <string name="Name">Shirt Graphic</string>
            <bool name="archivable">true</bool>
        </Properties>
    </Item>
</roblox>';
            break;
        case 'Pants':
            $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>
<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://www.roblox.com/roblox.xsd" version="4">
    <External>null</External>
    <External>nil</External>
    <Item class="Pants" referent="RBX188C484E5E2046CCAF2EC0D5BFBC7222">
        <Properties>
            <string name="Name">Clothing</string>
            <Content name="PantsTemplate"><url>' . $baseUrl . '/asset/?id=' . $decalId . '</url></Content>
        </Properties>
    </Item>
</roblox>';
            break;
        default:
            $xmlContent = '';
            break;
    }

    return $xmlContent;
}
?>
