<?php

namespace App\Controller\Administration;

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
    #[Route('/scadmin/tags', name: 'administration_tags')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function tagsAction(Request $request): Response
    {
        return $this->render('administration/tags.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/scadmin/tags-create', name: 'administration_tags-create')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createTagsAction(Request $request): Response
    {
        return $this->render('administration/tags_create.html.twig');
    }

    /**
     * @param Request $request
     * @param int $tagid
     * @return Response
     */
    #[Route('/scadmin/tags-edit/{tagid}', name: 'administration_tags-edit')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function editTagsAction(Request $request, int $tagid): Response
    {
        // dd(\Pimcore\Model\DataObject\Customer::getById($userid));
        return $this->render('administration/tags_edit.html.twig');
    }

    /**
     * @param Request $request
     * @param int $tagid
     * @return Response
     */
    #[Route('/scadmin/tags-delete/{tagid}', name: 'administration_tags-delete')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteTagsAction(Request $request, int $tagid): Response
    {
        $user = \Pimcore\Model\DataObject\Customer::getById($tagid);
        // $user->delete();

        return $this->redirectToRoute("administration_tags");
    }

}
