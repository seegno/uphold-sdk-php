<?php

require 'vendor/autoload.php';

require 'Command/CreateTokenCommand.php';

use Bitreserve\Command\CreateTokenCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Shell;

$application = new Application('Bitreserve SDK PHP');
$application->add(new CreateTokenCommand());

$application->run();
