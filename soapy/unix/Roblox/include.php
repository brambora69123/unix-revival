<?php 
  include_once 'Grid/Rcc/RCCServiceSoap.php';
 include_once 'Grid/Rcc/Job.php';
 include_once 'Grid/Rcc/ScriptExecution.php';
 include_once 'Grid/Rcc/LuaType.php';
 include_once 'Grid/Rcc/LuaValue.php';
 include_once 'Grid/Rcc/Status.php'; 
 $RCCServiceSoap = new Roblox\Grid\Rcc\RCCServiceSoap("181.215.226.46", 64988);



        $job = new Roblox\Grid\Rcc\Job(2,70);
  $scriptText = "print('walter white');";
  $script = new Roblox\Grid\Rcc\ScriptExecution("Lfao", $scriptText);
        $jobResult = $RCCServiceSoap->OpenJobEx($job, $script);
        print_r($jobResult);
 
  







