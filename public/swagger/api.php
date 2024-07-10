<?php

require dirname(__DIR__) . '/../vendor/autoload.php';

use OpenApi\Generator;

// Scan des contrôleurs pour générer la documentation
$openapi = Generator::scan([dirname(__DIR__) . '/App/Controllers/Api.php']);

// Définir les serveurs si nécessaire
$openapi->servers = [['url' => '/swagger']];

// Header pour indiquer que le contenu est JSON
header('Content-Type: application/json');

// Afficher le JSON généré
echo $openapi->toJson();