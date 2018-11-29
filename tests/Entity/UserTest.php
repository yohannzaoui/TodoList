<?php
declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserTest extends TestCase
{
    public function test_instance()
    {
        $user = new User();
        static::assertInstanceOf(UserInterface::class, $user);
        static::assertInstanceOf(ArrayCollection::class, $user->getTasks());
    }

    public function test_getters_setters_attributes()
    {
        $password = password_hash('password', PASSWORD_BCRYPT);
        $taskMock = $this->createMock(Task::class);

        $user = new User();
        $user->setPassword($password);
        $user->setUsername('UserTest');
        $user->setEmail('user-test@mail.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->addTask($taskMock);

        static::assertSame($password, $user->getPassword());
        static::assertSame('UserTest', $user->getUsername());
        static::assertSame('user-test@mail.com', $user->getEmail());
        static::assertContains('ROLE_ADMIN', $user->getRoles());
        static::assertContains($taskMock, $user->getTasks());
    }

    public function test_relation_method_with_task()
    {
        $taskMock1 = $this->createMock(Task::class);
        $taskMock2 = $this->createMock(Task::class);

        $user = new User();

        $user->addTask($taskMock1);
        static::assertContains($taskMock1, $user->getTasks());
        static::assertCount(1, $user->getTasks());

        $user->addTask($taskMock2);
        static::assertContains($taskMock1, $user->getTasks());
        static::assertContains($taskMock2, $user->getTasks());
        static::assertCount(2, $user->getTasks());

        $user->removeTask($taskMock1);
        static::assertNotContains($taskMock1, $user->getTasks());
        static::assertContains($taskMock2, $user->getTasks());
        static::assertCount(1, $user->getTasks());
        static::assertNull($user->getId());
        static::assertNull($user->getSalt());
    }
}
