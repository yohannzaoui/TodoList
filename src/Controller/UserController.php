<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
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
     *     name="users_list",
     *     methods={"GET"}
     * )
     *
     * @param UserRepository $repository
     *
     * @return Response
     */
    public function list(UserRepository $repository): Response
    {
        return $this->render('user/list_user.html.twig', ['users' => $repository->findAll()]);
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
     * @param SessionInterface $session
     *
     * @return Response
     */
    public function create(
        Request $request,
        UserRepository $repository,
        UserPasswordEncoderInterface $passwordEncoder,
        SessionInterface $session
    ): Response {
        $user = new User();

        $form = $this->createForm(UserType::class, $user, [
            'action' => $this->generateUrl('user_create')
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $repository->save($user);

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);
            $session->set('_security_main', serialize($token));

            $this->addFlash('success', 'L\'utilisateur a bien été ajouté.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/create_user.html.twig', ['form' => $form->createView()]);
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
    public function edit(
        Request $request,
        User $user,
        UserRepository $repository,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response {
        $this->denyAccessUnlessGranted('edit', $user);

        $form = $this->createForm(UserType::class, $user, [
            'action' => $this->generateUrl('user_edit', ['id' => $user->getId()])
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $repository->save($user);

            $this->addFlash('success', 'L\'utilisateur a bien été modifié');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/edit_user.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

    /**
     * @Route(
     *     "/users/{id}/delete",
     *     name="user_delete",
     *     methods={"GET"},
     *     requirements={"id"="\d+"}
     * )
     *
     * @param User $user
     * @param UserRepository $repository
     *
     * @return Response
     */
    public function delete(UserRepository $repository, User $user): Response
    {
        $repository->remove($user);

        $this->addFlash('success', 'L\'utilisateur '. $user->getUsername() .' a bien été supprimée.');

        return $this->redirectToRoute('users_list');
    }
}
