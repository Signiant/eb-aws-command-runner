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

?>
