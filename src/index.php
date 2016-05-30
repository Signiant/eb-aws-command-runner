<?php
	require 'vendor/autoload.php';
	require "lib/common.php";
  require "lib/config.php";
  require "lib/run_commands.php";

	ob_implicit_flush(TRUE);
	date_default_timezone_set('America/New_York');
?>

<?php
  $prompts = array();

  // Read the config File
  $appConfig = readConfig("config.yaml");

  // Setup the AWS Sdk
  $sharedConfig = [
  	'region' => $appConfig['eb_application']['region'],
  	'version' => 'latest'
  ];

  $sdk = new Aws\Sdk($sharedConfig);
  $ssmClient = $sdk->createSsm();

  // Get the prompts for the run command document
  $prompts = getCommandPrompts($ssmClient,getDocumentName($appConfig));

  // Show which env this will run against
  // Get the info for the command
  // Render the prompts for the command
  // Run the command
  // Get the results and display
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

      <div class="panel panel-default">
    		<div class="panel-heading">
    			<h3 class="panel-title">Elastic Beanstalk Command Runner</h3>
    		</div>
    			<div class='panel-body'>
    				This tool allows you to run a command on all EC2 instances in a beanstalk environment(s)
    				<p class="text-center">&nbsp;</p>
            The command will
            <?php
              print "<b>" . getCommandDisplay($appConfig) . "</b>";
            ?>
            from the EB application
            <?php
              print "<b>" . getEbApplication($appConfig) . "</b>";
             ?>
            <p class="text-center">&nbsp;</p>
            <form method='post' action='' class="form-horizontal" role="form">
              <?php
                renderPrompts($prompts);
               ?>
               <div class='form-group'>
                  <div class='col-sm-offset-3 col-sm-9'>
                    <button type='submit' class='btn btn-primary' name='submit'>Run Command</button>
                  </div>
                </div>
            </form>
    			</div>
    	</div> <!-- /panel -->

</div> <!-- /container -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
