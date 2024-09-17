<?php

namespace App\Controller\Bedarf;

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
    #[Route('/bedarf/archiv', name: 'bedarf-archiv')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function archivAction(Request $request): Response
    {
        // $this->checkPermission($this->getUser(), ["wegepate"]);

        return $this->render('bedarf/archiv.html.twig', [
        ]);
    }


    /**
     * @param Request $request
     * @param int $auftragid
     * @return Response
     */
    #[Route('/bedarf/archiv/{auftragid}', name: 'bedarf-archiv-details')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function archivDetailsAction(Request $request, int $auftragid): Response
    {
        // $this->checkPermission($this->getUser(), ["wegepate"]);

        return $this->render('bedarf/archiv-details.html.twig', [
        ]);
    }
}
