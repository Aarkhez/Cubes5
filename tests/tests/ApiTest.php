<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\Api;
use App\Models\Articles;
use App\Models\Cities;

class ApiTest extends TestCase
{
    public function testProductsAction()
    {

        // Rediriger la sortie pour capturer le JSON
        ob_start();
        $api = new Api([]);
        $api->ProductsAction(true);
        $output = ob_get_clean();

        $articles = json_decode($output, true);
        $this->assertEquals('Mappemonde à gratter', $articles[0]['name']);
        $this->assertEquals('Guide Berlin', $articles[1]['name']);
    }

    public function testCitiesAction()
    {
        ob_start();
        $_GET['query'] = 'Aix-en';
        $api = new Api([]);
        $api->CitiesAction(true);
        $output = ob_get_clean();

        $cities = json_decode($output, true);

        $this->assertIsArray($cities, 'La réponse doit être un tableau.');
        $this->assertCount(5, $cities, 'La réponse doit contenir 5 villes.');
        $cityNames = array_column($cities, 'Aix-en-provence');
    }
}
