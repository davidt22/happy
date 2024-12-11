<?php

namespace App\Infrastructure\Controller\Signing;

use App\Application\Signing\FindSigningsService;
use App\Application\Signing\FindUserSigningsService;
use App\Application\Signing\RegisterSigningService;
use App\Application\User\FindUserService;
use App\Domain\Entity\User\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SigningController extends AbstractController
{
    private FindUserService $findUserService;

    public function __construct(FindUserService $findUserService)
    {
        $this->findUserService = $findUserService;
    }

    #[Route("/signing/", name: "signing_main")]
    public function index(
        Request $request,
        FindUserSigningsService $findUserSigningsService,
        FindSigningsService $findSigningsService
    ): Response {
        $userEmail = $request->getSession()->get('userEmail');

        if (empty($userEmail)) {
            $this->addFlash('error', 'Usuario no logeado');

            return $this->redirectToRoute('app_login');
        }

        $user = $this->findUserService->execute($userEmail);

        if ($user instanceof Manager) {
            return $this->render('signing/indexManager.html.twig', [
                'user' => $user,
                'signings' => $findSigningsService->execute()
            ]);
        }

        return $this->render('signing/index.html.twig', [
            'user' => $user,
            'signings' => $findUserSigningsService->execute($user)
        ]);
    }

    #[Route("/signing/save", name: "signing_save")]
    public function saveSigningUser(Request $request, RegisterSigningService $registerSigningService): RedirectResponse
    {
        $userEmail = $request->getSession()->get('userEmail');

        if (empty($userEmail)) {
            $this->addFlash('error', 'Usuario no logeado');

            return $this->redirectToRoute('app_login');
        }

        $user = $this->findUserService->execute($userEmail);

        $startDate = $request->get('date_ini');
        $endDate = $request->get('date_end');

        try {
            $registerSigningService->execute($user, $startDate, $endDate);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('signing_main');
        }

        $this->addFlash('success', 'Fichaje correcto');

        return $this->redirectToRoute('signing_main');
    }
}
