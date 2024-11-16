<?php

namespace App\Controller\Administration;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CategoryController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/scadmin/category', name: 'administration_category')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function categoryAction(Request $request): Response
    {
        return $this->render('administration/category.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/scadmin/category-create', name: 'administration_category-create')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createCategoryAction(Request $request): Response
    {
        return $this->render('administration/category_create.html.twig');
    }

    /**
     * @param Request $request
     * @param int $kategorieid
     * @return Response
     */
    #[Route('/scadmin/category-edit/{kategorieid}', name: 'administration_category-edit')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function editCategoryAction(Request $request, int $kategorieid): Response
    {
        // dd(\Pimcore\Model\DataObject\Customer::getById($kategorieid));
        return $this->render('administration/category_edit.html.twig');
    }

    /**
     * @param Request $request
     * @param int $kategorieid
     * @return Response
     */
    #[Route('/scadmin/category-delete/{kategorieid}', name: 'administration_category-delete')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteCategoryAction(Request $request, int $kategorieid): Response
    {
        // $user = \Pimcore\Model\DataObject\Customer::getById($kategorieid);
        // $user->delete();

        return $this->redirectToRoute("administration_category");
    }
}
