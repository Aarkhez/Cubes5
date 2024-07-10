<?php

require dirname(__DIR__) . '/../vendor/autoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$openapi = \OpenApi\Generator::scan([__DIR__ . '/../../App/Controllers']);
header('Content-Type: application/json');
echo $openapi->toJSON();
