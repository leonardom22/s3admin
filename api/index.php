<?php

error_reporting(0);

require_once '../vendor/autoload.php';

use App\Core;

define('DS', DIRECTORY_SEPARATOR);

try {
    $application = new Core\Application(dirname(__DIR__));
    echo $application->start()->getContent();
} catch (Exception $e) {
    echo 'Runtime error.';
}