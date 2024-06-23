<?php

namespace App\Controller\Verwaltung;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TagsController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/verwaltung/tags', name: 'verwaltung_tags')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function testAction(Request $request): Response
    {
        return $this->render('verwaltung/tags.html.twig');
    }

}
