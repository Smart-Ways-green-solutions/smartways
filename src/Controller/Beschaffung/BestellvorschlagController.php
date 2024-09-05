<?php

namespace App\Controller\Beschaffung;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BestellvorschlagController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/bestellvorschlag/bestellvorschlag', name: 'bestellvorschlag')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function bestellvorschlagAction(Request $request): Response
    {
        // $this->checkPermission($this->getUser(), ["wegepate"]);

        return $this->render('beschaffung/bestellvorschlag.html.twig', [
        ]);
    }

}
