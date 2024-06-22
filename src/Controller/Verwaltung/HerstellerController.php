<?php

namespace App\Controller\Verwaltung;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HerstellerController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/verwaltung/hersteller', name: 'verwaltung_hersteller')]
    public function testAction(Request $request): Response
    {
        return $this->render('verwaltung/hersteller.html.twig');
    }

}
