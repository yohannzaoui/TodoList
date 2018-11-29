<?php
declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

final class TaskTest extends TestCase
{
    public function test_constructor()
    {
        $task = new Task();

        static::assertInstanceOf(\DateTime::class, $task->getCreatedAt());
        static::assertFalse($task->isDone());
    }

    public function test_getters_setters_attributes()
    {
        $authorMock = $this->createMock(User::class);

        $task = new Task();
        $task->setAuthor($authorMock);
        $task->setTitle('Titre');
        $content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam quis justo sit amet est porta tincidunt eu sit amet tortor. 
        Etiam ultrices congue mauris tincidunt tincidunt. Proin molestie massa sed pharetra bibendum. 
        Nulla eu finibus purus, sed ultrices ligula. Mauris lobortis placerat erat non placerat. Cras porta nulla nec enim.'. PHP_EOL;
        $task->setContent($content);
        $task->toggle();

        static::assertInstanceOf(User::class, $task->getAuthor());
        static::assertSame($authorMock, $task->getAuthor());
        static::assertSame('Titre', $task->getTitle());
        static::assertSame($content, $task->getContent());
        static::assertTrue($task->isDone());
        static::assertLessThanOrEqual(100, strlen($task->getShortContent()));
        static::assertNull($task->getId());

        $otherAuthor = $this->createMock(User::class);
        $task->setAuthor($otherAuthor);
        static::assertSame($otherAuthor, $task->getAuthor());

        $task->setContent('');
        static::assertLessThanOrEqual(100, strlen($task->getShortContent()));
    }

    public function test_user_have_tasks()
    {
        $user = new User();
        $task = new Task();

        $task->setAuthor($user);

        static::assertContains($task, $user->getTasks());
    }
}
