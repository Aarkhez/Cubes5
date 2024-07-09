<?php

use PHPUnit\Framework\TestCase;
use App\Models\Cities;
use Core\Model;

class CitiesTest extends TestCase
{
    protected static $db;

    public static function setUpBeforeClass(): void
    {
        self::$db = Model::getDB(); // Assurez-vous que votre méthode getDB() fonctionne correctement
    }

    public function testSearch()
    {
        // Assurez-vous qu'il y a des villes dans votre base de données qui commencent par "Paris"
        $result = Cities::search('Paris');
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        // Vérifiez que le résultat contient des IDs de ville
        foreach ($result as $id) {
            $this->assertIsInt((int)$id);
        }
    }
}