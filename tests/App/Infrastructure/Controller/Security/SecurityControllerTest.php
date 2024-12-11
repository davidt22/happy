<?php

namespace App\Infrastructure\Controller\Security;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    protected const BASE_URL = 'http://localhost:25000';
    protected static ?KernelBrowser $baseClient = null;

    protected function setUp(): void
    {
        parent::setUp();

        if (null === self::$baseClient) {
            self::$baseClient = static::createClient();
        }
    }

    public function testHomepageSuccess()
    {
        $crawler = self::$baseClient->request(Request::METHOD_GET, self::BASE_URL . '/');

        $header = $crawler->filter(".row.text-center.header")->count();

        $this->assertEquals(Response::HTTP_OK, self::$baseClient->getResponse()->getStatusCode());
        $this->assertEquals(1, $header);

        $welcomeText = str_contains($crawler->html(), 'Identificate');
        $this->assertTrue($welcomeText);
    }

    public function testLoginSuccess()
    {
        $crawler = self::$baseClient->request(Request::METHOD_GET, self::BASE_URL . '/');

        $form = $crawler->selectButton('Acceder')->form();
        $form['email'] = 'davter@happydonia.com';
        $form['password'] = '1234';

        $crawler = self::$baseClient->submit($form);

        $helloTextExists = str_contains($crawler->html(), 'Hola: david');
        $this->assertTrue($helloTextExists);

        $signingsListExists = str_contains($crawler->html(), 'Mis Registros');
        $this->assertTrue($signingsListExists);
    }
}
