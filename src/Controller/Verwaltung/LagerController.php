<?php

namespace App\Controller\Verwaltung;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LagerController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/verwaltung/lager', name: 'verwaltung_lager')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function testAction(Request $request): Response
    {
        return $this->render('verwaltung/lager.html.twig');
    }

}