<?php

namespace App\Controller\Verwaltung;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class KategorieController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/verwaltung/kategorie', name: 'verwaltung_kategorie')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function kategorieAction(Request $request): Response
    {
        return $this->render('verwaltung/kategorie.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/verwaltung/kategorie-anlegen', name: 'verwaltung_kategorie-anlegen')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createKategorieAction(Request $request): Response
    {
        return $this->render('verwaltung/kategorie_anlegen.html.twig');
    }

    /**
     * @param Request $request
     * @param int $kategorieid
     * @return Response
     */
    #[Route('/verwaltung/kategorie-bearbeiten/{kategorieid}', name: 'verwaltung_kategorie-bearbeiten')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function editKategorieAction(Request $request, int $kategorieid): Response
    {
        // dd(\Pimcore\Model\DataObject\Customer::getById($kategorieid));
        return $this->render('verwaltung/kategorie_bearbeiten.html.twig');
    }

    /**
     * @param Request $request
     * @param int $kategorieid
     * @return Response
     */
    #[Route('/verwaltung/lager-loeschen/{kategorieid}', name: 'verwaltung_lager-loeschen')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteKategorieAction(Request $request, int $kategorieid): Response
    {
        // $user = \Pimcore\Model\DataObject\Customer::getById($kategorieid);
        // $user->delete();

        return $this->redirectToRoute("verwaltung_kategorie");
    }
}
