<?php
    include_once 'Grid/Rcc/RCCServiceSoap.php';
    include_once 'Grid/Rcc/rccrender.php';
 include_once 'Grid/Rcc/Job.php';
 include_once 'Grid/Rcc/ScriptExecution.php';
 include_once 'Grid/Rcc/LuaType.php';
 include_once 'Grid/Rcc/LuaValue.php';
 include_once 'Grid/Rcc/Status.php';
  $id = $_GET["id"];
 $RCC= new Roblox\Grid\Rcc\RCCRenderer("127.0.0.1", 48434);
 $path = $_SERVER['DOCUMENT_ROOT'] . '/Tools/RenderedUsers/'.$id.'.png';
  $newURL = "http://mulrbx.com/My/Colours.aspx";
 



       $jobid = "RENDER_".substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);

            $job = new Roblox\Grid\Rcc\Job($jobid,60);
$scriptText = file_get_contents('./luascripts/avat.lua') . " return start(\"" . $id . "\"" . ",\"http://" . $_SERVER['SERVER_NAME'] . "\");";
  $script = new Roblox\Grid\Rcc\ScriptExecution("Render", $scriptText);
        $jobResult = $RCC->OpenJobEx($job, $script);

                $img = base64_decode($jobResult[0]);
  file_put_contents($path ,$img);
  

  header('Location: '.$newURL);
  




   
              
  
        
  

 







