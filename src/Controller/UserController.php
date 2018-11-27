<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController.
 *
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @Route(
     *     "/users",
     *     name="user_list",
     *     methods={"GET"}
     * )
     *
     * @param UserRepository $repository
     *
     * @return Response
     */
    public function listAction(UserRepository $repository): Response
    {
        return $this->render('user/list.html.twig', ['users' => $repository->findAll()]);
    }

    /**
     * @Route(
     *     "/users/create",
     *     name="user_create",
     *     methods={"GET", "POST"}
     * )
     *
     * @param Request $request
     * @param UserRepository $repository
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return Response
     */
    public function createAction(
        Request $request,
        UserRepository $repository,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $repository->save($user);

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route(
     *     "/users/{id}/edit",
     *     name="user_edit",
     *     methods={"GET", "POST"},
     *     requirements={"id"="\d+"}
     * )
     *
     * @param Request $request
     * @param User $user
     * @param UserRepository $repository
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return Response
     */
    public function editAction(
        Request $request,
        User $user,
        UserRepository $repository,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $repository->save($user);

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
