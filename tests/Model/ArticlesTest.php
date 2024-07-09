<?php

use PHPUnit\Framework\TestCase;
use App\Models\Articles;
use Core\Model;

class ArticlesTest extends TestCase
{
protected static $db;

public static function setUpBeforeClass(): void
{
self::$db = Model::getDB(); // Assurez-vous que votre méthode getDB() fonctionne correctement
}

public function testGetAll()
{
$result = Articles::getAll('views');
$this->assertIsArray($result);
}

public function testGetOne()
{
$result = Articles::getOne(1); // Assurez-vous qu'il y a un article avec l'ID 1 dans votre base de données
$this->assertIsArray($result);
$this->assertCount(1, $result);
}

public function testAddOneView()
{
$initialViews = Articles::getOne(1)[0]['views'];
Articles::addOneView(1);
$updatedViews = Articles::getOne(1)[0]['views'];
$this->assertEquals($initialViews + 1, $updatedViews);
}

public function testGetByUser()
{
$result = Articles::getByUser(1); // Assurez-vous qu'il y a des articles pour l'utilisateur avec l'ID 1 dans votre base de données
$this->assertIsArray($result);
}

public function testGetSuggest()
{
$result = Articles::getSuggest();
$this->assertIsArray($result);
$this->assertLessThanOrEqual(10, count($result));
}

public function testSave()
{
    $data = [
    'name' => 'Test Article',
    'description' => 'This is a test article',
    'user_id' => 1
    ];
    $articleId = Articles::save($data);
    $this->assertIsInt($articleId);

    // Cleanup
    $stmt = self::$db->prepare('DELETE FROM articles WHERE id = ?');
    $stmt->execute([$articleId]);
}

public function testAttachPicture()
{
$articleId = 1; // Assurez-vous qu'il y a un article avec l'ID 1 dans votre base de données
$pictureName = 'test_picture.jpg';
Articles::attachPicture($articleId, $pictureName);
$article = Articles::getOne($articleId);
$this->assertEquals($pictureName, $article[0]['picture']);
}
}