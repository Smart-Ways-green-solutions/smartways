<?php

namespace App\Controller\Verwaltung;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BenutzerController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/verwaltung/benutzer', name: 'verwaltung_beutzer')]
    public function testAction(Request $request): Response
    {
        return $this->render('verwaltung/benutzer.html.twig');
    }

}
