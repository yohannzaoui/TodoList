<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Tests\AppWebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class TaskControllerTest extends AppWebTestCase
{
    public function test_tasks_list_display_all_task()
    {
        // Count the number of tasks on DB
        $nb = \count($this->getRepository(Task::class)->findAll());
        $this->entityManagerClose();

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'root',
            'PHP_AUTH_PW'   => 'root',
        ]);

        $crawler = $client->request('GET', '/tasks');

        $nbShown = $crawler->filter('.thumbnail')->count();

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        static::assertEquals($nb, $nbShown);
    }



    public function test_get_task_create()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'User0',
            'PHP_AUTH_PW'   => 'password0',
        ]);

        $client->request('GET', '/tasks/create');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }



    public function test_post_task_create()
    {
        // Count the number of task on DB
        $nb = \count($this->getRepository(Task::class)->findAll());
        $this->entityManagerClose();

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'User0',
            'PHP_AUTH_PW'   => 'password0',
        ]);

        $crawler = $client->request('GET', '/tasks/create');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form();

        $form['task[title]'] = 'Création d\'une tâche';
        $form['task[content]'] = 'Contenu d\'une tâche';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect(); // Redirect to task_list

        $nbShown = $crawler->filter('.thumbnail')->count();

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        static::assertEquals($nb + 1, $nbShown); // One Task more task is shown
    }



    public function test_post_edit_task()
    {
        $taskId = '3';

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'User0',
            'PHP_AUTH_PW'   => 'password0',
        ]);

        $crawler = $client->request('GET', "/tasks/$taskId/edit");
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());


        $form = $crawler->selectButton('Modifier')->form();

        $title = 'Tâche Modifié';
        $content = 'Contenu modifié';

        $form['task[title]'] = $title;
        $form['task[content]'] = $content;

        $client->submit($form);

        $taskEntity = $this->getRepository(Task::class)->findOneBy(['id' => $taskId]);
        $this->entityManagerClose();

        static::assertSame($title, $taskEntity->getTitle());
        static::assertSame($content, $taskEntity->getContent());
    }



    public function test_toggle_task()
    {
        $taskId = '3';
        $repository = $this->getRepository(Task::class);

        $taskBefore = $repository->findOneBy(['id' => $taskId]);

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'User0',
            'PHP_AUTH_PW'   => 'password0',
        ]);

        $client->request('GET', "/tasks/$taskId/toggle");

        $taskAfter = $repository->findOneBy(['id' => $taskId]);
        $this->entityManagerClose();

        switch ($taskBefore->isDone()) {
            case true: static::assertFalse($taskAfter->isDone());
                break;
            case false: static::assertTrue($taskAfter->isDone());
                break;
        }
    }


        
    public function test_delete_task()
    {
        $taskId = '3';

        $task = $this->getRepository(Task::class)->findOneBy(['id' => $taskId]);
        static::assertInstanceOf(Task::class, $task);

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'User0',
            'PHP_AUTH_PW'   => 'password0',
        ]);

        $client->request('GET', "/tasks/$taskId/delete");
        $this->assertTrue($client->getResponse()->isRedirect());

        $task = $this->getRepository(Task::class)->findOneBy(['id' => $taskId]);
        static::assertNull($task);
    }
}
