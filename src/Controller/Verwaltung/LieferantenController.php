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
    public function lieferantenAction(Request $request): Response
    {
        return $this->render('verwaltung/lieferanten.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/verwaltung/lieferanten-anlegen', name: 'verwaltung_lieferanten-anlegen')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createLieferantenAction(Request $request): Response
    {
        return $this->render('verwaltung/lieferanten_anlegen.html.twig');
    }

    /**
     * @param Request $request
     * @param int $lieferantenid
     * @return Response
     */
    #[Route('/verwaltung/lieferanten-bearbeiten/{lieferantenid}', name: 'verwaltung_lieferanten-bearbeiten')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function editLieferantenAction(Request $request, int $lieferantenid): Response
    {
        // dd(\Pimcore\Model\DataObject\Customer::getById($userid));
        return $this->render('verwaltung/lieferanten_bearbeiten.html.twig');
    }

    /**
     * @param Request $request
     * @param int $lieferantenid
     * @return Response
     */
    #[Route('/verwaltung/lieferanten-loeschen/{lieferantenid}', name: 'verwaltung_lieferanten-loeschen')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteLieferantenAction(Request $request, int $lieferantenid): Response
    {
        $user = \Pimcore\Model\DataObject\Customer::getById($lieferantenid);
        // $user->delete();

        return $this->redirectToRoute("verwaltung_lieferanten");
    }

}
