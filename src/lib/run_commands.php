<?php

function getCommandPrompts($ssmClient,$documentName)
{
  $result = array();
  $retVal = array();

  try {
    $result = $ssmClient->describeDocument([
      'Name' => $documentName
    ]);
  } catch (Exception $e) {
    error_log("getCommandPrompts EXCEPTION: " . $e->getMessage());
  }

  if ($result)
  {
    $retVal = $result['Document']['Parameters'];
  }

  return $retVal;
}

function runCommand($ssmClient,$documentName,$s3_bucket,$s3_prefix,$hash,$parameters,$instances)
{
  $commandID = "";
  $comment = "Run on " . date('l jS \of F Y h:i:s A');

  $commandArray = array(
    'Comment' => $comment,
    'DocumentHash' => $hash,
    'DocumentHashType' => Sha256,
    'DocumentName' => $documentName,
    'InstanceIds' => $instances,
    'Parameters' => $parameters
  );

  if ($s3_bucket)
  {
    $commandArray['OutputS3BucketName'] = $s3_bucket;
  }

  if ($s3_prefix)
  {
    $commandArray['OutputS3KeyPrefix'] = $s3_prefix;
  }

  try {
    $result = $ssmClient->sendCommand($commandArray);
  } catch (Exception $e) {
    error_log("runCommand EXCEPTION: " . $e->getMessage());
  }

  if ($result)
  {
    $commandID = $result['Command']['CommandId'];
  }

  return $commandID;
}

function getCommandOutput($ssmClient,$commandID)
{
  $result = array();
  $pending = true;
  $attempt = 1;
  $maxAttempts = 10;

  while ( ($pending == true) && ($attempt != $maxAttempts) )
  {
    try {
      $result = $ssmClient->listCommandInvocations([
        'CommandId' => $commandID,
        'Details' => true
      ]);
    } catch (Exception $e) {
      error_log("getCommandOutput EXCEPTION: " . $e->getMessage());
    }

    if ($result)
    {
      foreach ($result['CommandInvocations'] as $aResult)
      {
        $commandStatus = $aResult['CommandPlugins'][0]['Status'];
        error_log("Command ID " . $commandID . " Status: " . $commandStatus);
        if ($commandStatus != "Pending")
        {
          // If we're not pending, break out of this retry loop
          $pending = false;
        } else
        {
          $attempt++;
          sleep(1);
        }
      }
    }
  }

  return $result;
}
?>
