<?php

namespace App\Controller\Verwaltung;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class KategorieController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/verwaltung/kategorie', name: 'verwaltung_kategorie')]
    public function testAction(Request $request): Response
    {
        return $this->render('verwaltung/kategorie.html.twig');
    }

}
