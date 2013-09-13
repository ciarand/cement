#!/usr/local/bin/php
<?php

if (!$loader = include __DIR__.'/vendor/autoload.php') {
    die('You must set up the project dependencies.');
}

// Personal namespaces
use RestlessCo\Cement\Core\Application;

$app = new Application();
$app->run();
