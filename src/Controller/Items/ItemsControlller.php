<?php

namespace App\Controller\Items;

use App\Controller\BaseController;
use App\Model\Customer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ItemsControlller extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/items/items', name: 'items')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function itemsAction(Request $request): Response
    {
        $this->checkPermission($this->getUser(), ["wegepate"]);

        return $this->render('items/items.html.twig', [

        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/items/items-create', name: 'items-create')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createItemsAction(Request $request): Response
    {
        return $this->render('items/items_create.html.twig');
    }

    /**
     * @param Request $request
     * @param int $artikelid
     * @return Response
     */
    #[Route('/items/items-edit/{artikelid}', name: 'items-edit')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function editItemsAction(Request $request, int $artikelid): Response
    {
        // dd(\Pimcore\Model\DataObject\Customer::getById($userid));
        return $this->render('items/items_edit.html.twig');
    }

    /**
     * @param Request $request
     * @param int $userid
     * @return Response
     */
    #[Route('/items/items-delete/{artikelid}', name: 'items-delete')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteItemsAction(Request $request, int $artikeld): Response
    {
        // $user->delete();

        return $this->redirectToRoute("items");
    }
}
