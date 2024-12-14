<?php

namespace App\Controller\Administration;

use App\Controller\BaseController;
use App\Model\Customer;
use App\Form\CustomerFormType;
use CustomerManagementFrameworkBundle\CustomerSaveValidator\Exception\DuplicateCustomerException;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UsersController extends BaseController implements BaseAdministrationController
{

    private $moduleName = 'User';
    
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
    #[Route('/scadmin/users-edit/{userid}', name: 'administration_users-edit')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createUserAction(Request $request, $userid=null): Response
    {


        if ($userid) {
            $object = Customer::getById($userid);
            
            if(empty($object)){
                throw $this->createNotFoundException($this->moduleName.' is not found');
            }
        }else{
            $object = new Customer();
        }


        $form = $this->createForm(CustomerFormType::class,$object);

        // Handle the request
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the form data
            $values = $request->request->all();


            $session = $request->getSession();
            if($values['password'] == $values['password-repeated']){
                $data = $form->getData();
                $id = $data->getId();
            
                if(isset($values['permission_admin_v'])){
                    $data->setPermission_admin(true);
                }
                if(isset($values['permission_wegepate_v'])){
                    $data->setPermission_wegepate(true);
                }
                if(isset($values['permission_lagerist_v'])){
                    $data->setPermission_lagerist(true);
                }
                if(isset($values['permission_wegemanager_v'])){
                    $data->setPermission_wegemanager(true);
                }
               
                try{

                    $data->setPublished(true)
                        ->save();

                    if($userid){
                        $session->getFlashBag()->set('success', $this->moduleName.' has been updated successfully');
                    }else{
                        $session->getFlashBag()->set('success', $this->moduleName.' has been created successfully');
                    }
                } catch (DuplicateCustomerException $ex) {
                    $session->getFlashBag()->set('danger',  $ex->getMessage());
                }
                
            }else{
                $session->getFlashBag()->set('danger',  'Password and Confirm Password do not match. Please ensure both fields are identical.');
            }
           
            

            return $this->redirectToRoute('administration_users');
        }
        //*if($request->isMethod('post')) {
          
        /*  $values = [
                ...$request->request->all(),
                'permission_wegepate' => !empty($request->get('permission_wegepate'))?true:false,
                'permission_wegemanager' => !empty($request->get('permission_wegemanager'))?true:false,
                'permission_lagerist' => !empty($request->get('permission_lagerist'))?true:false,
                'permission_admin' => !empty($request->get('permission_admin'))?true:false,
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
            }*/
            //dd($request);
          //  return $this->redirectToRoute('administration_users');
        //}
        return $this->render('administration/users_create.html.twig',[
            'form' => $form->createView(),
            'userid' => $userid
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
        $session = $request->getSession();
        $session->getFlashBag()->set('success', $this->moduleName.' has been deleted successfully');
        return $this->redirectToRoute("administration_users");
    }
}
