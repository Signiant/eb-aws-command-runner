<?php

function getEBInstances($ebClient,$ebApp,$ebEnvs)
{
  $instances = array();

  foreach ($ebEnvs as $ebEnv)
  {
    $envInstances = getEBEnvInstances($ebClient,$ebApp,$ebEnv);

    if (empty($instances))
    {
      $instances = $envInstances;
    } else {
        $instances = array_merge($instances,$envInstances);
    }
  }
  return $instances;
}

function getEBEnvInstances($ebClient,$ebApp,$ebEnv)
{
  $instances = array();
  $envToQuery = "";

  $envToQuery = str_replace('"', "", $ebEnv);
  error_log("Getting instances for " . $envToQuery);

  try {
    $result = $ebClient->describeEnvironmentResources([
      'EnvironmentName' => $envToQuery
    ]);
  } catch (Exception $e) {
      error_log("getEBEnvInstances EXCEPTION: " . $e->getMessage());
  }

  if ($result)
  {
    foreach ($result['EnvironmentResources']['Instances'] as $instance)
    {
      array_push($instances,$instance['Id']);
    }
  }
  return $instances;
}
?>
