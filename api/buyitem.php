<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php'); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php'); ?>
<?php
switch(true){case($RBXTICKET == null):die(header('Location: '. $baseUrl .'/'));break;}
$ItemId = (int)($_GET['id'] ?? die(json_encode(['message' => 'Cannot handle request at this time.'])));
$HandleMethod = ($_GET['method'] ?? die(json_encode(['message' => 'Cannot handle request at this time.'])));

$AssetFetch = $MainDB->prepare("SELECT * FROM asset WHERE approved = '1' AND id = :pid AND public = '1'");
$AssetFetch->execute([":pid" => $ItemId]);
$Results = $AssetFetch->fetch(PDO::FETCH_ASSOC);

$NoPurchase = array('place', 'advertisement');
$errors = array();

switch(true){
	case ($Results):
		$ItemType = $Results['itemtype'];
		
		switch (true){
			case (!in_array($ItemType, $NoPurchase)):
				$AssetId = $Results['id'];
				$AssetName = $Results['name'];
				$CreatorID = $Results['creatorid'];
				$AssetFree = $Results['free'];
				$AssetType = $Results['itemtype'];
				
				$AP = $MainDB->prepare("SELECT * FROM bought WHERE boughtby = :id AND boughtid = :bid");
				$AP->execute([':id' => $id, ':bid' => $AssetId]);
				$Row = $AP->fetch(PDO::FETCH_ASSOC);
				switch(true){case ($Row):die(header('Location: '. $baseUrl .'/alreadyowned'));break;}
				
				$SP = $MainDB->prepare("SELECT robux FROM users WHERE id = :id");
				$SP->execute([':id' => $CreatorID]);
				$SellerRow = $SP->fetch(PDO::FETCH_ASSOC);
				switch(true){case (!$SellerRow):die(header('Location: '. $baseUrl .'/cantfindem'));break;}
				
				switch ($Results["offsale"]) {
					case 1:
						die(header("Location: /itemisoffsale"));
						break;
				}

				$packageItems = [];
				if ($ItemType == "PackageBundle") {
					$packageItems = explode(',', trim($Results['packageItems'], '[]'));
				} else {
					$packageItems[] = $AssetId;
				}

				switch($HandleMethod){
					case "Robux":
						$AssetRS = $Results['rsprice'];
						
						switch(true){case($AssetRS > $robux):die(header('Location: '. $baseUrl .'/nobux'));break;}
						$WasteRobux = $robux - $AssetRS;
						$WinRobux = $SellerRow['robux'] + $AssetRS;

						$UpdateRS = $MainDB->prepare("UPDATE users SET robux=? WHERE token=?")->execute([$WasteRobux, $RBXTICKET]);
						$UpdateSeller = $MainDB->prepare("UPDATE users SET robux=? WHERE id=?")->execute([$WinRobux, $CreatorID]);

						if ($ItemType == "PackageBundle") {
							$InsertBundle = $MainDB->prepare("INSERT INTO `bought` (`boughtby`, `boughtid`, `boughtname`, `itemtype`, `wearing`, `boughtfrom`) VALUES (?, ?, ?, ?, NULL, ?)")
								->execute([$id, $AssetId, $AssetName, $ItemType, $CreatorID]);
						}

						foreach ($packageItems as $PackageItemId) {
							$PackageItemId = (int)trim($PackageItemId);
							$PackageFetch = $MainDB->prepare("SELECT * FROM asset WHERE approved = '1' AND id = :pid AND public = '1'");
							$PackageFetch->execute([":pid" => $PackageItemId]);
							$PackageResults = $PackageFetch->fetch(PDO::FETCH_ASSOC);
							if (!$PackageResults) {
								continue; 
							}

							$InsertToDB = $MainDB->prepare("INSERT INTO `bought` (`boughtby`, `boughtid`, `boughtname`, `itemtype`, `wearing`, `boughtfrom`) VALUES (?, ?, ?, ?, NULL, ?)")
								->execute([$id, $PackageItemId, $PackageResults['name'], $PackageResults['itemtype'], $CreatorID]);
						}

						die(header('Location: '. $baseUrl .'/catalog'));
						break;

					case "Free":
						switch ($AssetFree){
							case "1":
								if ($ItemType == "PackageBundle") {
									$InsertBundle = $MainDB->prepare("INSERT INTO `bought` (`boughtby`, `boughtid`, `boughtname`, `itemtype`, `wearing`, `boughtfrom`) VALUES (?, ?, ?, ?, NULL, ?)")
										->execute([$id, $AssetId, $AssetName, $ItemType, $CreatorID]);
								}

								foreach ($packageItems as $PackageItemId) {
									$PackageItemId = (int)trim($PackageItemId);
									$PackageFetch = $MainDB->prepare("SELECT * FROM asset WHERE approved = '1' AND id = :pid AND public = '1'");
									$PackageFetch->execute([":pid" => $PackageItemId]);
									$PackageResults = $PackageFetch->fetch(PDO::FETCH_ASSOC);
									if (!$PackageResults) {
										continue; 
									}

									$InsertToDB = $MainDB->prepare("INSERT INTO `bought` (`boughtby`, `boughtid`, `boughtname`, `itemtype`, `wearing`, `boughtfrom`) VALUES (?, ?, ?, ?, NULL, ?)")
										->execute([$id, $PackageItemId, $PackageResults['name'], $PackageResults['itemtype'], $CreatorID]);
								}
								die(header('Location: '. $baseUrl .'/'));
								break;

							default:
								die(header('Location: '. $baseUrl .'/'));
								break;
						}
						break;

					default:
						die(json_encode(['message' => 'Method handle is not valid for usage in purchase.']));
						break;
				}
				
				break;
			default:
				die(json_encode(['message' => 'Unable to purchase item.']));
				break;
		}
		
		break;
	default:
		die(json_encode(['message' => 'Cannot handle this request at this time.']));
		break;
}
?>
