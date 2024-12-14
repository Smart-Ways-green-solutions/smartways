<?php

namespace App\Controller\Requirement;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ArchiveController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/requirement/archive', name: 'requirement-archive')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function archiveAction(Request $request): Response
    {
        // $this->checkPermission($this->getUser(), ["wegepate"]);

        return $this->render('requirement/archive.html.twig', [
        ]);
    }


    /**
     * @param Request $request
     * @param int $auftragid
     * @return Response
     */
    #[Route('/requirement/archive/{auftragid}', name: 'requirement-archive-details')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function archiveDetailsAction(Request $request, int $auftragid): Response
    {
        // $this->checkPermission($this->getUser(), ["wegepate"]);

        return $this->render('requirement/archive-details.html.twig', [
        ]);
    }
}
