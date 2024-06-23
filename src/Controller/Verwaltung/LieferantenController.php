<?php

namespace App\Controller\Verwaltung;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LieferantenController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/verwaltung/lieferanten', name: 'verwaltung_lieferanten')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function testAction(Request $request): Response
    {
        return $this->render('verwaltung/lieferanten.html.twig');
    }

}
