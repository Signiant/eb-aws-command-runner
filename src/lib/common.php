<?php

function dump_var($var)
{
	print "<pre>";print_r($var);print "</pre>";
}

function timestamp_sort($a,$b)
{
	return $a['timestamp'] < $b['timestamp'];
}

function doAlert($type,$text)
{
	print "<div class='alert " . $type . "'>\n";
	print "<strong>" . $text . "</strong>\n";
	print "</div>\n";
}

function getPromptVals($POST)
{
	$promptArray = array();

	foreach ($POST as $prompt => $promptVal)
	{
		if ($prompt == "submit") { continue; }

		$promptArray[$prompt] = array($promptVal);
	}

	return $promptArray;
}

function renderPrompts($promptsArray,$POST)
{
	foreach ($promptsArray as $prompt)
	{
		if ($POST && array_key_exists($prompt['Name'],$POST))
		{
			// There is a value already set so pre-fill the form with it
			$defaultValue = $POST[$prompt['Name']];
		}else
		{
			// Use the default value from the prarm on the document
			$defaultValue = str_replace('"', "", $prompt['DefaultValue']);
		}

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
