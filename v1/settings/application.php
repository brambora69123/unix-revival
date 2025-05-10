<?php
if (isset($_GET['applicationName'])) {
    $applicationName = $_GET['applicationName'];

    if ($applicationName === 'PCDesktopClient') {
        $jsonFilePath = __DIR__ . '/pc.json';
    } elseif ($applicationName === 'RCCServiceI5zJ23w1ZCJEvakMJK23') {
        $jsonFilePath = __DIR__ . '/rcc.json';
    } elseif ($applicationName === 'PCClientBootstrapper') {
        $jsonFilePath = __DIR__ . '/bootstrapper.json';
    } elseif ($applicationName === 'AndroidApp') {
        $jsonFilePath = __DIR__ . '/android.json';
	} elseif ($applicationName === 'RCCServiceh933tM8fvZgwys6SDn1XdFwR5jOPtSXPgv') {
        $jsonFilePath = __DIR__ . '/rcc2.json';
	} elseif ($applicationName === 'RCCServiceh933tM8fvZgwys6SDn1XdFwR5jOPtSXPgb') {
	$jsonFilePath = __DIR__ . '/rccnew.json';
	} elseif ($applicationName === 'PCDesktopCliene') {
		$jsonFilePath = __DIR__ . '/pcnew.json';
	} elseif ($applicationName === 'PCClientBootstrappe1') {
	$jsonFilePath = __DIR__ . '/boot.json';
    } elseif ($applicationName === 'PCStudioApp') {
		$jsonFilePath = __DIR__ . '/pcnew.json'; 
	} elseif ($applicationName === 'StudioApp') {
	$jsonFilePath = __DIR__ . '/pc.json';
	} else {
        echo json_encode(['error' => 'Invalid applicationName']);
        exit;
    }

    if (file_exists($jsonFilePath)) {
        $jsonContent = file_get_contents($jsonFilePath);

        header('Content-Type: application/json');
        echo json_encode(['applicationSettings' => json_decode($jsonContent)]);
        exit;
    } else {
        echo json_encode(['error' => $applicationName . '.json file not found']);
        exit;
    }
} else {
    echo json_encode(['error' => 'applicationName parameter is required']);
    exit;
}
?>
