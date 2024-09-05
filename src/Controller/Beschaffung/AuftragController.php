<?php

namespace App\Controller\Beschaffung;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AuftragController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/bestellvorschlag/auftrag', name: 'bestellvorschlag-auftrag')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function artikelAction(Request $request): Response
    {
        $this->checkPermission($this->getUser(), ["wegepate"]);

        return $this->render('beschaffung/auftrag.html.twig', [
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/bestellvorschlag/auftrag-anlegen', name: 'bestellvorschlag-auftrag-anlegen')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createArtikelAction(Request $request): Response
    {
        return $this->render('beschaffung/auftrag-anlegen.html.twig');
    }

    /**
     * @param Request $request
     * @param int $auftragid
     * @return Response
     */
    #[Route('/bestellvorschlag/auftrag-bearbeiten/{auftragid}', name: 'bestellvorschlag-auftrag-bearbeiten')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function editArtikelAction(Request $request, int $auftragid): Response
    {
        // dd(\Pimcore\Model\DataObject\Customer::getById($userid));
        return $this->render('beschaffung/auftrag-details.html.twig');
    }

    /**
     * @param Request $request
     * @param int $auftragid
     * @return Response
     */
    #[Route('/bestellvorschlag/auftrag-loeschen/{auftragid}', name: 'bestellvorschlag-auftrag-loeschen')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteArtikelAction(Request $request, int $auftragid): Response
    {
        // $user->delete();

        return $this->redirectToRoute("bestellvorschlag-auftrag");
    }
}
