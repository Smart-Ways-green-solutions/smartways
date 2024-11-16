<?php

namespace App\Controller\Procurement;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class OrderproposalController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/procurement/orderproposal', name: 'procurement-orderproposal')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function orderproposalAction(Request $request): Response
    {
        // $this->checkPermission($this->getUser(), ["wegepate"]);

        return $this->render('procurement/order-proposal.html.twig', [
        ]);
    }

}
