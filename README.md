# eb-aws-command-runner
A small PHP wrapper around the AWS SSM run commands to run commands on EB instances contained within Elastic Beanstalk environments.

The app will dynamically read the parameters defined for the run command document and render simple text prompts to allow input.  Currently, only the String type is supported.

# Prerequisites

* The SSM agent must be installed on the beanstalk instances (use the beanstalk extension contained within this project)
* The EC2 run command document must exist in the same region as the Beanstalk application
* The IAM user this is run as must have permissions to use the Run command document

# Configuration

The app is driven by a small YAML configuration file that can be mounted into the docker container using a bind mount.  An example file looks like:

```YAML
eb_application:
  name: "my-eb-application"
  region: us-east-1
  beanstalk_envs:
    "my-eb-env-a"
    "my-eb-env-b"

command:
  display: "Check External Network Connectivity"
  document: "remote_server_connectivity_test"
  hash: "12345678901234567890123456789012345678901234567890"
  s3bucket: "output-buckets-for-results"
  s3keyprefix: "myprefix"
```
* document is the name of the EC2 Run Command document
* Hash is the sha256 hash associated with the document (obtained via the AWS Console)
* s3bucket is a pre-existing S3 bucket where the command output will be placed
* s3keyprefix is a key (folder) in the bucket where the command output will be placed

# Usage
## On an EC2 instance with a role configured to allow access to DynamoDB
```bash
docker run -d -p -v /config/config.yaml:config.yaml 8080:80 signiant/eb-aws-command-runner
```
## On an machine outside EC2
```bash
docker run -d -p 8080:80
              -e "AWS_ACCESS_KEY_ID=XXXX" \
              -e "AWS_SECRET_ACCESS_KEY=XXXX" \
              -v /config/config.yaml:config.yaml
              signiant/eb-aws-command-runner
```
For the above execution, you can then access the tool using http://MY_DOCKER_HOST:8080

## Alternate Config Files

You can pass in alternate configuration files using the query string key **config**

For example, if you had run the container as follows:
```bash
docker run -d -p \
              -v /config/config.yaml:config.yaml \
              -v /config/moreconfig.yaml:moreconfig.yaml \
              8080:80 signiant/eb-aws-command-runner
```
You could then access the alternate config using http://MY_DOCKER_HOST:8080/index.php?config=moreconfig.yaml

## Optional Menu File

The app will check for the existance of a menu.php file and if present use that to render a boostrap nav bar.  For example, if you create a menu.php file like:

```HTML
      <!-- Static navbar -->
      <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="http://www.signiant.com">Signiant DevOps</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-wrench"></span> Dropdown 1 <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="http://www.signiant.com" target="_blank">Signiant 1</a></li>
                <li class="divider"></li>
                <li><a href="http://www.signiant.com" target="_blank"><span class="glyphicon glyphicon-cloud"></span> Signiant 2</a></li>
              </ul>
            </li>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="http://status.signiant.com" target="_blank"><span class="glyphicon glyphicon-ok-sign"></span> Signiant Services Status</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
```

and then run the container as follows:

```bash
docker run -d -p \
              -v /config/config.yaml:config.yaml \
              -v /config/menu.php:menu.php \
              8080:80 signiant/eb-aws-command-runner
```

You'll get a menu rendered at the top with the options you've added.
