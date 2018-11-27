<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TaskController.
 *
 * @package App\Controller
 */
class TaskController extends AbstractController
{
    /**
     * @Route(
     *     "/tasks",
     *     name="task_list",
     *     methods={"GET"}
     * )
     *
     * @param TaskRepository $repository
     *
     * @return Response
     */
    public function listAction(TaskRepository $repository): Response
    {
        return $this->render('task/list.html.twig', ['tasks' => $repository->findAll()]);
    }

    /**
     * @Route(
     *     "/tasks/create",
     *     name="task_create",
     *     methods={"GET", "POST"}
     * )
     *
     * @param Request $request
     * @param TaskRepository $repository
     *
     * @return Response
     */
    public function createAction(Request $request, TaskRepository $repository): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($task);

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route(
     *     "/tasks/{id}/edit",
     *     name="task_edit",
     *     methods={"GET", "POST"},
     *     requirements={"id"="\d+"}
     * )
     *
     * @param Task $task
     * @param TaskRepository $repository
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(Task $task, TaskRepository $repository, Request $request): Response
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($task);

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route(
     *     "/tasks/{id}/toggle",
     *     name="task_toggle",
     *     methods={"GET", "POST"},
     *     requirements={"id"="\d+"}
     * )
     *
     * @param Task $task
     * @param TaskRepository $repository
     *
     * @return Response
     */
    public function toggleTaskAction(Task $task, TaskRepository $repository): Response
    {
        $task->toggle();
        $repository->save($task);

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route(
     *     "/tasks/{id}/delete",
     *     name="task_delete",
     *     methods={"GET"},
     *     requirements={"id"="\d+"}
     * )
     *
     * @param Task $task
     * @param TaskRepository $repository
     *
     * @return Response
     */
    public function deleteTaskAction(TaskRepository $repository, Task $task): Response
    {
        $repository->remove($task);

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
