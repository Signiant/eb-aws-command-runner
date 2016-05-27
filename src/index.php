<?php
	require 'vendor/autoload.php';
	require "lib/common.php";
	//require "lib/bounce.php";

	ob_implicit_flush(TRUE);
	date_default_timezone_set('America/New_York');
?>

<?php
  // Read the config File
  $appConfig = readConfig("config.yaml");
  #print "<PRE>"; print_r($appConfig); print "</PRE>";
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
    		<form method='post' action='' class="form-horizontal" role="form">
    			<div class='panel-body'>
    				This tool allows you to run a command on all EC2 instances in a beanstalk environment(s)
    				<p class="text-center">&nbsp;</p>
            <div class="form-group">
              <label for="eb_app" class="col-sm-2 control-label">Beanstalk Application</label>
              <div class="col-sm-10">
              <select class='form-control' name='eb_app'>\
                <?php
                displayChooserOptions($appConfig);
                 ?>
              </select>
            </div>

          </div>
          <div class='form-group'>
            <div class='col-sm-offset-2 col-sm-10'>
              <button type='submit' class='btn btn-primary' name='submit'>Run Command</button>
            </div>
          </div>
    				</div>
    				&nbsp;
    		</form>
    	</div> <!-- /panel -->

</div> <!-- /container -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
