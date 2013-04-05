<?php

use Flagbit\Plantuml\Command\WriteCommand;
use Symfony\Component\Console\Application;

require dirname(__DIR__) . '/vendor/autoload.php';

set_time_limit(0);

$application = new Application();

$application->add(new WriteCommand());

$application->run();
