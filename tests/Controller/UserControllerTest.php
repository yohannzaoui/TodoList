<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\AppWebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class UserControllerTest extends AppWebTestCase
{
    public function test_list_user()
    {
        // Count the number of users on DB
        $nb = \count($this->getRepository(User::class)->findAll());
        $this->entityManagerClose();

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'root',
            'PHP_AUTH_PW'   => 'root',
        ]);

        $crawler = $client->request('GET', '/users');

        $nbShown = $crawler->filter('.table tbody tr')->count();

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        static::assertEquals($nb, $nbShown);
    }


    public function test_create_user()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/users/create');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('S\'inscrire')->form();

        $form['user[username]'] = 'TestUserCreate';
        $form['user[password][first]'] = 'passwordTest';
        $form['user[password][second]'] = 'passwordTest';
        $form['user[email]'] = 'testusercreate@gmail.com';
        $form['user[roles]'] = 'ROLE_USER';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());

        $user = $this->getRepository(User::class)->findOneBy(['username' => 'TestUserCreate']);
        $this->entityManagerClose();

        static::assertSame('TestUserCreate', $user->getUsername());
        static::assertSame('testusercreate@gmail.com', $user->getEmail());
        static::assertSame(['ROLE_USER'], $user->getRoles());
    }


    public function test_edit_user()
    {
        $userBefore = $this->getRepository(User::class)->findOneBy(['username' => 'TestUserCreate']);

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'TestUserCreate',
            'PHP_AUTH_PW'   => 'passwordTest',
        ]);

        $crawler = $client->request('GET', '/users/'. $userBefore->getId() .'/edit');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());


        $form = $crawler->selectButton('Modifier')->form();

        $form['user[username]'] = 'TestUserModifier';
        $form['user[password][first]'] = 'passwordTest';
        $form['user[password][second]'] = 'passwordTest';
        $form['user[email]'] = 'testusermodify@gmail.com';
        $form['user[roles]'] = 'ROLE_ADMIN';

        $client->submit($form);

        $userAfter = $this->getRepository(User::class)->findOneBy(['id' => $userBefore->getId()]);
        $this->entityManagerClose();

        static::assertSame('TestUserModifier', $userAfter->getUsername());
        static::assertNotSame($userBefore->getPassword(), $userAfter->getPassword());
        static::assertSame('testusermodify@gmail.com', $userAfter->getEmail());
        static::assertSame(['ROLE_ADMIN'], $userAfter->getRoles());
    }


    public function test_delete_user()
    {
        $user = $this->getRepository(User::class)->findOneBy(['id' => '7']);
        static::assertInstanceOf(User::class, $user); // Assert if User exists

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'root',
            'PHP_AUTH_PW'   => 'root',
        ]);

        $client->request('GET', '/users/7/delete');
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();

        $h1 = $crawler->filter('h1')->text();
        static::assertSame('Liste des utilisateurs', $h1);

        $user = $this->getRepository(User::class)->findOneBy(['id' => '7']);
        $this->entityManagerClose();
        static::assertNull($user);
    }
}
