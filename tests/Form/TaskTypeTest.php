<?php
declare(strict_types=1);

namespace App\Tests\Form;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\TypeTestCase;

final class TaskTypeTest extends TypeTestCase
{
    public function testImplements()
    {
        $type = new TaskType();

        static::assertInstanceOf(AbstractType::class, $type);
    }

    public function testSubmitValidData()
    {
        $data = [
            'title' => 'titre',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam quis justo sit amet est porta tincidunt eu sit amet tortor. 
                Etiam ultrices congue mauris tincidunt tincidunt. Proin molestie massa sed pharetra bibendum. 
                Nulla eu finibus purus, sed ultrices ligula. Mauris lobortis placerat erat non placerat. Cras porta nulla nec enim.'
        ];


        $type = $this->factory->create(TaskType::class, new Task());
        $type->submit($data);

        static::assertTrue($type->isValid());
        static::assertTrue($type->isSubmitted());
        static::assertInstanceOf(Task::class, $type->getData());
        static::assertSame($data['title'], $type->get('title')->getData());
        static::assertSame($data['content'], $type->get('content')->getData());
    }
}
