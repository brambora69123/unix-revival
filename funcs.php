<?php  
 include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
 
function nxe($input) {
  if (!is_string($input)) {
    return '';
  }
  return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}
  function sign($data) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
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
  
 function execInBackgroundWindows($filePath, $workingDirectory, $arguments)
{
    $cmd = escapeshellcmd("\"$filePath\" $arguments");
    $fullCmd = "start /B \"\" $cmd";
    $process = popen($fullCmd, "r");
    if ($process === false) {
        throw new Exception('Failed to execute the command in the background.');
    }
    pclose($process);
}
  
function CreateRcc($port, $year, $jobid) {
  $exePaths = [
    2015 => 'C:\\rccs\\rcc15\\RCCPatched.exe',
    2017 => 'C:\\rccs\\rcc17\\RCCService.exe',
    2019 => 'C:\\rccs\\rcc19\\RCCService.exe',
    2021 => 'C:\\rccs\\rcc21\\RCCService.exe'
  ];

  $workingDirs = [
    2015 => 'C:\\rccs\\rcc15\\',
    2017 => 'C:\\rccs\\rcc17\\',
    2019 => 'C:\\rccs\\rcc19\\',
    2021 => 'C:\\rccs\\rcc21\\'
  ];

  if (!isset($exePaths[$year])) {
    throw new Exception('Invalid year provided.');
  }
  $exePath = $exePaths[$year];
  $workingDir = $workingDirs[$year];

  $args = "-console -start -port $port";

  // check if the port is in use -bamar
  while (isPortInUse($port)) {
    $port++; // Use the next available port
  }

  execInBackgroundWindows($exePath, $workingDir, $args);

  try {
    global $MainDB;
    $sql = "INSERT INTO open_rccs (port, year, jobid) VALUES (:port, :year, :jobid)";
    $stmt = $MainDB->prepare($sql);
    $stmt->bindParam(':port', $port, PDO::PARAM_INT);
    $stmt->bindParam(':year', $year, PDO::PARAM_INT);
    $stmt->bindParam(':jobid', $jobid, PDO::PARAM_STR);
    $stmt->execute();
  } catch (PDOException $e) {
    throw new Exception('Database error: ' . $e->getMessage());
  }
}

function isPortInUse($port) {
  $command = "netstat -ano | findstr :$port";
  exec($command, $output, $return_var);
  return $return_var === 0 && !empty($output);
}

function RemoveRcc($jobid) {
	 include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
    try {
   
        $sql = "SELECT * FROM open_rccs WHERE jobid = :jobid";
        $stmt = $MainDB->prepare($sql);
        $stmt->bindParam(':jobid', $jobid, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new Exception('No record found with the provided jobid.');
        }

        $port = $row['port'];
        $year = $row['year'];

        $exePaths = [
            2015 => 'C:\\rccs\\rcc15\\RCCPatched.exe',
            2017 => 'C:\\rccs\\rcc17\\RCCService.exe',
            2019 => 'C:\\rccs\\rcc19\\RCCService.exe',
            2021 => 'C:\\rccs\\rcc21\\RCCService.exe'
        ];

        if (!isset($exePaths[$year])) {
            throw new Exception('Invalid year provided in the record.');
        }
        $exePath = $exePaths[$year];

        $command = "netstat -ano | findstr :$port";
        exec($command, $output, $return_var);

        if ($return_var !== 0 || empty($output)) {
            throw new Exception('Failed to find the process using the specified port.');
        }

        $lines = explode("\n", trim(implode("\n", $output)));
        $pid = null;

        foreach ($lines as $line) {
            $parts = preg_split('/\s+/', trim($line));
            if (isset($parts[4])) {
                $pid = $parts[4];
                break;
            }
        }

        if (!$pid) {
            throw new Exception('Failed to determine the process ID.');
        }

        $killCommand = "taskkill /F /PID $pid";
        exec($killCommand, $killOutput, $killReturnVar);

        if ($killReturnVar !== 0) {
            throw new Exception('Failed to kill the process with the specified PID.');
        }

        $sql = "DELETE FROM open_rccs WHERE jobid = :jobid";
        $stmt = $MainDB->prepare($sql);
        $stmt->bindParam(':jobid', $jobid, PDO::PARAM_STR);
        $stmt->execute();

    } catch (PDOException $e) {
        throw new Exception('Database error: ' . $e->getMessage());
    } catch (Exception $e) {
        throw new Exception('Error: ' . $e->getMessage());
    }
}

  
 
  
  function random_tkn(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
  ): string {
    if ($length < 1) {
      throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
      $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
  }
  
  function getGameBar($num1, $num2){
    $PlusTotal = $num1 + $num2;
    switch(true){case($PlusTotal == 0):return 0;break;}
    $TotalGraph = 100 * $num1 / $PlusTotal;
    return $TotalGraph;
  }
  
  function appCheckRedirect($State){
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
    switch(true){
      case (in_array($_SERVER['HTTP_USER_AGENT'], $rbxUserAgent)):
        switch($State){
          case "Games":
            die(header("Location: ". $baseUrl ."/MobileView/Games.aspx"));
            break;
          case "Catalog":
            die(header("Location: ". $baseUrl ."/MobileView/Catalog/"));
            break;
          case "Profile":
            die(header("Location: ". $baseUrl ."/MobileView/My/Home.aspx"));
            break;
          default:
            die(header("Location: ". $baseUrl ."/MobileView/My/Home.aspx"));
            break;
        }
        break;
    }
  }
?>
