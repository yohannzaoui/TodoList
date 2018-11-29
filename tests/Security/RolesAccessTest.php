<?php
declare(strict_types=1);

namespace App\Tests\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class RolesAccessTest extends WebTestCase
{
    public function test_home_redirection_when_anonymous()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        static::assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $formValues = $crawler->filter('form')->form()->getValues();

        static::assertArrayHasKey('login[_username]', $formValues);
        static::assertArrayHasKey('login[_password]', $formValues);
        static::assertArrayHasKey('login[_token]', $formValues);
    }

    public function test_home_when_authenticate()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'User0',
            'PHP_AUTH_PW'   => 'password0',
        ]);

        $crawler = $client->request('GET', '/');

        $h1 = $crawler->filter('h1')->text();

        static::assertSame('Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !', $h1);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * @param string $uri
     * @param int $expectedStatusCode
     *
     * @dataProvider role_user_can_accessProvider
     */
    public function test_role_user_can_access(string $uri, int $expectedStatusCode)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'User0',
            'PHP_AUTH_PW'   => 'password0',
        ]);

        $client->request('GET', $uri);

        $this->assertEquals($expectedStatusCode, $client->getResponse()->getStatusCode());
    }

    /**
     * @return \Generator
     */
    public function role_user_can_accessProvider()
    {
        yield ['/tasks', Response::HTTP_OK];
        yield ['/tasks/create', Response::HTTP_OK];
        yield ['/tasks/1/edit', Response::HTTP_OK];
        yield ['/tasks/1/toggle', Response::HTTP_FOUND];
        yield ['/tasks/1/delete', Response::HTTP_FOUND];
        yield ['/users/create', Response::HTTP_OK];
        yield ['/users/1/edit', Response::HTTP_OK]; // attempt to access own user edition
    }

    /**
     * @param string $uri
     *
     * @dataProvider role_user_can_NOT_accessProvider
     */
    public function test_role_user_can_NOT_access(string $uri)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'User0',
            'PHP_AUTH_PW'   => 'password0',
        ]);

        $client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /**
     * @return \Generator
     */
    public function role_user_can_NOT_accessProvider()
    {
        yield ['/users'];
        yield ['/users/2/edit']; // attempt to access other user edition
        yield ['/users/0/delete']; // attempt to delete it's own profil
        yield ['/users/2/delete']; // attempt to delete other user profil
    }

    /**
     * @param string $uri
     * @param int $expectedStatusCode
     *
     * @dataProvider role_admin_can_accessProvider
     */
    public function test_role_admin_can_access(string $uri, int $expectedStatusCode)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'root',
            'PHP_AUTH_PW'   => 'root',
        ]);

        $client->request('GET', $uri);

        $this->assertEquals($expectedStatusCode, $client->getResponse()->getStatusCode());
    }

    /**
     * @return \Generator
     */
    public function role_admin_can_accessProvider()
    {
        yield ['/tasks', Response::HTTP_OK];
        yield ['/tasks/create', Response::HTTP_OK];
        yield ['/tasks/2/edit', Response::HTTP_OK];
        yield ['/tasks/2/toggle', Response::HTTP_FOUND];
        yield ['/tasks/2/delete', Response::HTTP_FOUND];
        yield ['/users', Response::HTTP_OK];
        yield ['/users/create', Response::HTTP_OK];
        yield ['/users/1/edit', Response::HTTP_OK]; // attempt to access other user edition
        yield ['/users/6/edit', Response::HTTP_OK]; // attempt to access own user edition
        yield ['/users/1/edit', Response::HTTP_OK];
    }
}
