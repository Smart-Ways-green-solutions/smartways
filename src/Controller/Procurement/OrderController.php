<?php

namespace App\Controller\Procurement;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class OrderController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/procurement/order', name: 'procurement-order')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function orderAction(Request $request): Response
    {
        $this->checkPermission($this->getUser(), ["wegepate"]);

        return $this->render('procurement/order.html.twig', [
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/procurement/order-create', name: 'procurement-order-create')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createOrderAction(Request $request): Response
    {
        return $this->render('procurement/order-create.html.twig');
    }

    /**
     * @param Request $request
     * @param int $auftragid
     * @return Response
     */
    #[Route('/procurement/order-edit/{auftragid}', name: 'procurement-order-edit')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function editOrderAction(Request $request, int $auftragid): Response
    {
        // dd(\Pimcore\Model\DataObject\Customer::getById($userid));
        return $this->render('procurement/order-edit.html.twig');
    }

    /**
     * @param Request $request
     * @param int $auftragid
     * @return Response
     */
    #[Route('/procurement/order-delete/{auftragid}', name: 'procurement-order-delete')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteOrderAction(Request $request, int $auftragid): Response
    {
        // $user->delete();

        return $this->redirectToRoute("procurement-order");
    }
}
