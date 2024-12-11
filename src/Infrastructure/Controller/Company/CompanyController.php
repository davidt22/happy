<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Company;

use App\Application\Company\UpdateCompanyService;
use App\Application\User\FindUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/company")
 */
class CompanyController extends AbstractController
{
    private FindUserService $findUserUseCase;

    public function __construct(FindUserService $findUserUseCase)
    {
        $this->findUserUseCase = $findUserUseCase;
    }

    /**
     * @Route("/update", name="company-update")
     */
    public function udpdate(Request $request, UpdateCompanyService $updateCompanyService): Response
    {
        $startHour = $request->get('start_hour');
        $endHour = $request->get('end_hour');
        $workingDays = $request->get('workdays');

        $userEmail = $request->getSession()->get('userEmail');
        if (empty($userEmail)) {
            $this->addFlash('error', 'Usuario no logeado');

            return $this->redirectToRoute('app_login');
        }

        $user = $this->findUserUseCase->execute($userEmail);

        $updateCompanyService->execute($user->company(), $startHour, $endHour, $workingDays);

        $this->addFlash('success', 'Configuracion actualizada.');

        return $this->redirectToRoute('signing_main');
    }
}
