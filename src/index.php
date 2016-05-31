<?php
	require 'vendor/autoload.php';
	require "lib/common.php";
  require "lib/config.php";
  require "lib/run_commands.php";
  require "lib/beanstalk.php";

	ob_implicit_flush(TRUE);
	date_default_timezone_set(@date_default_timezone_get());
?>

<?php
  $prompts = array();
  $ebInstances = array();

  // Read the config File
  $configFile = $_GET['config'];
  if (empty($configFile)) { $configFile = 'config.yaml'; }

  $appConfig = readConfig($configFile);

  // Setup the AWS Sdk
  $sharedConfig = [
  	'region' => getRegion($appConfig),
  	'version' => 'latest'
  ];

  $sdk = new Aws\Sdk($sharedConfig);
  $ssmClient = $sdk->createSsm();
  $ebClient = $sdk->createElasticBeanstalk();

  // Get the prompts for the run command document
  $prompts = getCommandPrompts($ssmClient,getDocumentName($appConfig));
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">

    <title>Elastic Beanstalk Command Runner</title>
    <!-- Bootstrap core CSS -->
    <link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="navbar.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
      <?php if (file_exists("menu.php")) { include 'menu.php'; } ?>

      <div class="panel panel-default">
    		<div class="panel-heading">
    			<h3 class="panel-title">Elastic Beanstalk Command Runner</h3>
    		</div>
    			<div class='panel-body'>
    				This tool allows you to run a command on all EC2 instances in a beanstalk environment(s)
    				<p class="text-center">&nbsp;</p>

            The command will
            <?php print "<b>" . getCommandDisplay($appConfig) . "</b>"; ?>
            from the EB application
            <?php print "<b>" . getEbApplication($appConfig) . "</b>"; ?>
             in region <?php print "<b>" . getRegion($appConfig) . "</b>"; ?>

            <p class="text-center">&nbsp;</p>
            <form method='post' action='' class="form-horizontal" role="form">
              <?php
                renderPrompts($prompts,$_POST);
               ?>
               <div class='form-group'>
                  <div class='col-sm-offset-3 col-sm-9'>
                    <button type='submit' class='btn btn-primary' name='submit'>Run Command</button>
                  </div>
                </div>
            </form>
    			</div>
    	</div> <!-- /panel -->

<?php
  if ( (isset($_POST["submit"])) )
  {
    print "<div class='panel panel-default'>\n";
  	print "<div class='panel-heading'>\n";
  	print "<h3 class='panel-title'>Results</h3>\n";
  	print "</div>\n";
  	print "<div class='panel-body'>\n";

    // Get the prompt values
    $promptVals = getPromptVals($_POST);

    // get the instances for the EB app
    $ebInstances = getEBInstances($ebClient,getEbApplication($appConfig),getEBEnvs($appConfig));

    if ($ebInstances)
    {
      $commandID = runCommand($ssmClient,getDocumentName($appConfig),getCommandS3Bucket($appConfig),getCommandS3KeyPrefix($appConfig),getDocumentHash($appConfig),$promptVals,$ebInstances);

      if ($commandID)
      {
        $commandOutput = getCommandOutput($ssmClient,$commandID);

        if ($commandOutput)
        {
          outputCommandResults($commandOutput);
        } else {
          doAlert('alert-warning',"No output returned from the command execution");
        }
        // TODO format the output and display in the panel
      } else {
        doAlert('alert-danger',"Unable to run command - check logs for details");
      }
    } else {
      doAlert('alert-danger',"No Running E2 instances found for EB application " . getEbApplication($appConfig));
    }

    print "</div>"; // Panel body
  }
?>

</div> <!-- /container -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
