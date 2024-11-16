<?php

namespace App\Controller\Administration;

use App\Controller\BaseController;
use App\Model\Customer;
use CustomerManagementFrameworkBundle\CustomerSaveValidator\Exception\DuplicateCustomerException;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UsersController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/scadmin/users', name: 'administration_users')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function userAction(Request $request): Response
    {
        // $this->checkPermission($this->getUser(), ["wegepate"]);

        $currentUsers = new \Pimcore\Model\DataObject\Customer\Listing();

        return $this->render('administration/users.html.twig', [
            'currentUsers' => $currentUsers,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    #[Route('/scadmin/users-create', name: 'administration_users-create' )]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createUserAction(Request $request): Response
    {
        if($request->isMethod('post')) {
            $values = [
                ...$request->request->all(),
                'permission_wegepate' => !empty($request->get('permission_wegepate')),
                'permission_wegemanager' => !empty($request->get('permission_wegemanager')),
                'permission_lagerist' => !empty($request->get('permission_lagerist')),
                'permission_admin' => !empty($request->get('permission_admin')),
            ];

            try {
                Customer::create($values)
                    ->setPublished(true)
                    ->save();
            } catch(DuplicateCustomerException $ex) {
                return $this->render('administration/users_create.html.twig', [
                    'old' => $values,
                    'error' => $ex
                ]);
            }
            //dd($request);
            return $this->redirectToRoute('administration_users');
        }
        return $this->render('administration/users_create.html.twig');
    }

    /**
     * @param Request $request
     * @param int $userid
     * @return Response
     */
    #[Route('/scadmin/users-edit/{userid}', name: 'administration_users-edit')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function editUserAction(Request $request, int $userid): Response
    {
        // dd(\Pimcore\Model\DataObject\Customer::getById($userid));
        $customer = \Pimcore\Model\DataObject\Customer::getById($userid);
        if(empty($customer))
            return $this->redirectToRoute('administration_users');

        $values = [
            ...$request->request->all(),
            'permission_wegepate' => !empty($request->get('permission_wegepate')),
            'permission_wegemanager' => !empty($request->get('permission_wegemanager')),
            'permission_lagerist' => !empty($request->get('permission_lagerist')),
            'permission_admin' => !empty($request->get('permission_admin')),
        ];

        if($request->isMethod('post')) {
            try {
                $customer->setValues($values)->save();
            } catch (DuplicateCustomerException $ex) {
                return $this->render('administration/users_edit.html.twig', [
                    'old' => $values,
                    'customer' => $customer,
                    'error' => $ex
                ]);
            }
            return $this->redirectToRoute('administration_users');
        }

        return $this->render('administration/users_edit.html.twig', [
            'customer' => $customer
        ]);
    }

    /**
     * @param Request $request
     * @param int $userid
     * @return Response
     */
    #[Route('/scadmin/users-delete/{userid}', name: 'administration_users-delete')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteUserAction(Request $request, int $userid): Response
    {
        $user = \Pimcore\Model\DataObject\Customer::getById($userid);
        $user->delete();

        return $this->redirectToRoute("administration_users");
    }
}
