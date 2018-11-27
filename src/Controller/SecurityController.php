<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route(
     *     "/login",
     *     name="login",
     *     methods={"GET"}
     * )
     * @Security("has_role('IS_AUTHENTICATED_ANONYMOUSLY')")
     *
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route(
     *     "/login_check",
     *     name="login_check",
     *     methods={"POST"}
     * )
     * @Security("has_role('IS_AUTHENTICATED_ANONYMOUSLY')")
     */
    public function loginCheck()
    {
        // This code is never executed.
    }

    /**
     * @Route(
     *     "/logout",
     *     name="logout",
     *     methods={"POST"}
     * )
     * @Security("has_role('IS_AUTHENTICATED_ANONYMOUSLY')")
     */
    public function logoutCheck()
    {
        // This code is never executed.
    }
}
