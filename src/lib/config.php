<?php
function getEbApplication($appConfig)
{
	$ebApp = $appConfig['eb_application']['name'];
	return $ebApp;
}

function getDocumentName($appConfig)
{
	return $appConfig['command']['document'];
}

function getDocumentHash($appConfig)
{
  return $appConfig['command']['hash'];
}

function getCommandDisplay($appConfig)
{
	return $appConfig['command']['display'];
}
?>
