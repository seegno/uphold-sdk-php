<?php

require 'vendor/autoload.php';

require 'Command/CreateTokenCommand.php';

use Uphold\Command\CreateTokenCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Shell;

$application = new Application('Uphold SDK PHP');
$application->add(new CreateTokenCommand());

$application->run();
