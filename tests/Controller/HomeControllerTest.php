<?php

namespace App\Tests\Controller;

use App\Controller\HomeController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testIndexExists(): void
    {
        $homeController = new HomeController();
        $this->assertTrue(
            method_exists($homeController, 'index')
        );
    }
}
