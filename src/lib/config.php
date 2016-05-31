<?php
function readConfig($filename)
{
	// Read the config from a YAML file and put the config entries in a named array
	$config_yaml = Spyc::YAMLLoad($filename);
	return ($config_yaml);
}

function getEbApplication($appConfig)
{
	return $appConfig['eb_application']['name'];
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

function getCommandS3Bucket($appConfig)
{
	return $appConfig['command']['s3bucket'];
}

function getCommandS3KeyPrefix($appConfig)
{
	return $appConfig['command']['s3keyprefix'];
}

function getRegion($appConfig)
{
	return $appConfig['eb_application']['region'];
}

function getEBEnvs($appConfig)
{
	return $appConfig['eb_application']['beanstalk_envs'];
}
?>
