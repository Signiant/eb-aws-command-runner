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

function runCommand($ssmClient,$documentName,$hash,$parameters,$instances)
{
  $commandID = "";
  $comment = "Run on " . date('l jS \of F Y h:i:s A');

  try {
    $result = $ssmClient->sendCommand([
      'Comment' => $comment,
      'DocumentHash' => $hash,
      'DocumentHashType' => Sha256,
      'DocumentName' => $documentName,
      'InstanceIds' => $instances,
      'Parameters' => $parameters
    ]);
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
  $output = array();

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
    // TODO handle pending status
    dump_var($result['CommandInvocations']);
  }

  return $output;
}
?>
