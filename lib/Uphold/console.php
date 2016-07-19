<?php

require 'vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Shell;
use Uphold\Command\CreateTokenCommand;
use Uphold\Command\RevokeTokenCommand;

$application = new Application('Uphold SDK PHP');
$application->add(new CreateTokenCommand());
$application->add(new RevokeTokenCommand());

$application->run();
