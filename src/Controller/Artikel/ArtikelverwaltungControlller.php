<?php

namespace App\Controller\Artikel;

use App\Controller\BaseController;
use App\Model\Customer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ArtikelverwaltungControlller extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/artikel/artikelverwaltung', name: 'artikel')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function artikelAction(Request $request): Response
    {
        $this->checkPermission($this->getUser(), ["wegepate"]);

        return $this->render('artikel/artikel.html.twig', [

        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/artikel/artikel-anlegen', name: 'artikel-anlegen')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createArtikelAction(Request $request): Response
    {
        return $this->render('artikel/artikel_anlegen.html.twig');
    }

    /**
     * @param Request $request
     * @param int $artikelid
     * @return Response
     */
    #[Route('/artikel/artikel-bearbeiten/{artikelid}', name: 'artikel-bearbeiten')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function editArtikelAction(Request $request, int $artikelid): Response
    {
        // dd(\Pimcore\Model\DataObject\Customer::getById($userid));
        return $this->render('artikel/artikel_bearbeiten.html.twig');
    }

    /**
     * @param Request $request
     * @param int $userid
     * @return Response
     */
    #[Route('/artikel/artikel-loeschen/{artikelid}', name: 'artikel-loeschen')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteArtikelAction(Request $request, int $artikeld): Response
    {
        // $user->delete();

        return $this->redirectToRoute("artikel_verwaltung");
    }
}
