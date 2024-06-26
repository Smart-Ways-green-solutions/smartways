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
    public function tagsAction(Request $request): Response
    {
        return $this->render('verwaltung/tags.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/verwaltung/tags-anlegen', name: 'verwaltung_tags-anlegen')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createTagsAction(Request $request): Response
    {
        return $this->render('verwaltung/tags_anlegen.html.twig');
    }

    /**
     * @param Request $request
     * @param int $tagid
     * @return Response
     */
    #[Route('/verwaltung/tags-bearbeiten/{tagid}', name: 'verwaltung_tags-bearbeiten')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function editTagsAction(Request $request, int $tagid): Response
    {
        // dd(\Pimcore\Model\DataObject\Customer::getById($userid));
        return $this->render('verwaltung/tags_bearbeiten.html.twig');
    }

    /**
     * @param Request $request
     * @param int $tagid
     * @return Response
     */
    #[Route('/verwaltung/tags-loeschen/{tagid}', name: 'verwaltung_tags-loeschen')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteTagsAction(Request $request, int $tagid): Response
    {
        $user = \Pimcore\Model\DataObject\Customer::getById($tagid);
        // $user->delete();

        return $this->redirectToRoute("verwaltung_tags");
    }

}
