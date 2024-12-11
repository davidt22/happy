<?php

namespace App\Infrastructure\Controller\Security;

use App\Application\User\FindUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route("/", name: "app_login")]
    public function login(): Response
    {
        return $this->render('security/login.html.twig');
    }

    #[Route("/do-login", name: "app_do_login")]
    public function doLogin(Request $request, FindUserService $findUserService)
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $user = $findUserService->execute($email);

        if ($user->password() === $password) {
            $request->getSession()->set('userEmail', $email);

            return $this->redirectToRoute('signing_main');
        }

        $this->addFlash('error', 'Datos incorrectos');

        return $this->redirectToRoute('app_login');
    }

    #[Route("/logout", name: "app_logout")]
    public function logout(Request $request): RedirectResponse
    {
        $request->getSession()->clear();

        return $this->redirectToRoute('app_login');
    }
}
