<?php

namespace App\Controller\Beschaffung;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ArchivController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/bestellvorschlag/archiv', name: 'bestellvorschlag-archiv')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function archivAction(Request $request): Response
    {
        // $this->checkPermission($this->getUser(), ["wegepate"]);

        return $this->render('beschaffung/archiv.html.twig', [
        ]);
    }


    /**
     * @param Request $request
     * @param int $auftragid
     * @return Response
     */
    #[Route('/bestellvorschlag/archiv/{auftragid}', name: 'bestellvorschlag-archiv-details')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function archivDetailsAction(Request $request, int $auftragid): Response
    {
        // $this->checkPermission($this->getUser(), ["wegepate"]);

        return $this->render('beschaffung/archiv-details.html.twig', [
        ]);
    }
}
