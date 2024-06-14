<?php
// tests/Controllers/ProductTest.php

use PHPUnit\Framework\TestCase;
use App\Controllers\Product;
use Core\View;

class ProductTest extends TestCase
{
protected $productController;

protected function setUp(): void
{
$this->productController = new Product([]);
}

public function testShowAction()
{
// Test de la méthode
$this->expectOutputString('');
$this->productController->showAction();

// Vérifiez que la vue est rendue avec les bons paramètres
$this->assertSame('Product/Show.html', View::$template);
$this->assertSame('Test Article', View::$variables['article']['name']);
}

public function testContactAction()
{
// Test de la méthode
$this->expectOutputString('');
$this->productController->contactAction();

// Vérifiez que la vue est rendue correctement
$this->assertSame('Product/contact.html', View::$template);
}
}