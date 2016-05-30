<?php

function readConfig($filename)
{
	// Read the config from a YAML file and put the config entries in a named array
	$config_yaml = Spyc::YAMLLoad($filename);
	return ($config_yaml);
}

function timestamp_sort($a,$b)
{
	return $a['timestamp'] < $b['timestamp'];
}

function renderPrompts($promptsArray)
{
	foreach ($promptsArray as $prompt)
	{
		$defaultValue = str_replace('"', "", $prompt['DefaultValue']);

		print "<div class='form-group'>\n";
		print "\t<label class='control-label col-sm-3' for='". $prompt['Name'] . "''>" . $prompt['Name'] . "</label>\n";
		print "\t<div class='col-sm-9'>\n";
		print "\t\t<div class='input-group'>\n";
		print "\t\t\t<input type='text' class='form-control' id='" . $prompt['Name'] . "' name='" . $prompt['Name'] . "' value='" . $defaultValue . "'>\n";
		print "\t\t\t<div class='input-group-addon'>\n";
		print "\t\t\t\t<span class='glyphicon glyphicon-info-sign' title='" . $prompt['Description'] . "'></span>\n";
		print "\t\t\t</div>\n";
		print "\t\t</div>\n";
		print "\t</div>\n";
		print "</div>\n";
	}
}
?>
