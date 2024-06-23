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
    #[Route('/artikel/artikelverwaltung', name: 'artikel_verwaltung')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function artikelAction(Request $request): Response
    {
        $this->checkPermission($this->getUser(), ["wegepate"]);

        $currentUsers = new \Pimcore\Model\DataObject\Customer\Listing();

        return $this->render('artikel/artikelverwaltung.html.twig', [
            'currentUsers' => $currentUsers,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/artikel/artikel-anlegen', name: 'artikel_verwaltung-anlegen')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createArtikelAction(Request $request): Response
    {
        return $this->render('');
    }

    /**
     * @param Request $request
     * @param int $userid
     * @return Response
     */
    #[Route('/artikel/artikel-bearbeiten/{userid}', name: 'artikel_verwaltung-bearbeiten')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function editArtikelAction(Request $request, int $userid): Response
    {
        // dd(\Pimcore\Model\DataObject\Customer::getById($userid));
        return $this->render('');
    }

    /**
     * @param Request $request
     * @param int $userid
     * @return Response
     */
    #[Route('/artikel/artikel-loeschen/{artikelid}', name: 'artikel_verwaltung-loeschen')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteArtikelAction(Request $request, int $artikeld): Response
    {
        // $user->delete();

        return $this->redirectToRoute("artikel_verwaltung");
    }
}
