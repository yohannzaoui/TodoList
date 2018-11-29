<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class DefaultControllerTest extends WebTestCase
{
    public function test_index()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'root',
            'PHP_AUTH_PW'   => 'root',
        ]);
        $crawler = $client->request('GET', '/');

        $h1 = $crawler->filter('h1')->text();

        static::assertSame('Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !', $h1);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
