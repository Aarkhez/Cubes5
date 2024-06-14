<?php

use PHPUnit\Framework\TestCase;
use Core\View;

class HomeViewTest extends TestCase
{
    public function testRenderHomePage()
    {
        $output = View::renderTemplate('Home/index.html', [
            'name' => 'Toto',
            'colours' => ['rouge', 'bleu', 'vert']
        ]);

        $this->assertStringContainsString('Toto', $output);
        $this->assertStringContainsString('rouge', $output);
        $this->assertStringContainsString('bleu', $output);
        $this->assertStringContainsString('vert', $output);
    }
}