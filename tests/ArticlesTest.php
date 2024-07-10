<?php

use PHPUnit\Framework\TestCase;
use App\Models\Articles;

class ArticlesTest extends TestCase
{
    private $db;

    protected function setUp(): void
    {
        // Configurer la connexion à la base de données
        $this->db = new PDO('sqlite::memory:'); // Utilisation de SQLite en mémoire pour les tests
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Créer la table articles et users
        $this->db->exec('
            CREATE TABLE articles (
                id INTEGER PRIMARY KEY,
                name TEXT,
                description TEXT,
                user_id INTEGER,
                published_date TEXT,
                views INTEGER DEFAULT 0,
                picture TEXT
            )
        ');

        $this->db->exec('
            CREATE TABLE users (
                id INTEGER PRIMARY KEY,
                username TEXT
            )
        ');

        // Ajouter des données de test
        $this->db->exec('INSERT INTO users (id, username) VALUES (1, "testuser")');
        $this->db->exec('INSERT INTO articles (id, name, description, user_id, published_date) VALUES (1, "Test Article", "Description", 1, "2023-01-01")');
    }

    protected function tearDown(): void
    {
        // Fermer la connexion à la base de données
        $this->db = null;
    }

    public function testSave()
    {
        $data = [
            'name' => 'New Article',
            'description' => 'New Description',
            'user_id' => 1
        ];
        $articleId = Articles::save($data);
        $result = Articles::getOne($articleId);
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('New Article', $result[0]['name']);
    }

    public function testAttachPicture()
    {
        Articles::attachPicture(1, 'picture.jpg');
        $result = Articles::getOne(1);
        $this->assertEquals('picture.jpg', $result[0]['picture']);
    }
}
