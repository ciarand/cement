#!/usr/bin/env php
<?php

if (!include __DIR__.'/vendor/autoload.php') {
    die('You must set up the project dependencies.');
}

chdir(__DIR__);

// Personal namespaces
use RestlessCo\Cement\Console\Application;

$app = new Application();
$app->run();
