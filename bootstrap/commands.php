<?php

use Symfony\Component\Console\Application;
use App\Console\Commands\GenerateKeyCommand;
use App\Console\Commands\StartCommand;

require __DIR__ . '/../vendor/autoload.php';

$application = new Application();

// Add commands
$application->add(new GenerateKeyCommand());
$application->add(new StartCommand());

$application->run();
