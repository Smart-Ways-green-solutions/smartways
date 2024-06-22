<?php

namespace App\Controller;

use App\Form\LoginFormType;
use App\Form\PasswordMaxLengthTrait;
use App\Services\PasswordRecoveryService;
use Pimcore\Translation\Translator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class LoginController extends BaseController
{
    use PasswordMaxLengthTrait;

    /**
     * @Route("/login", name="account-login")
     *
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     * @param UserInterface|null $user
     *
     * @return Response
     */
    public function loginAction(
        AuthenticationUtils $authenticationUtils,
        Request $request,
        UserInterface $user = null
    ): Response
    {

        //redirect user to index page if logged in
        if ($user && $this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // dd($error);

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $formData = [
            '_username' => $lastUsername
        ];

        $form = $this->createForm(LoginFormType::class, $formData, [
            'action' => $this->generateUrl("account-login.de")
        ]);

        //store referer in session to get redirected after login
        //if (!$request->get('no-referer-redirect')) {
        //    $request->getSession()->set('_security.demo_frontend.target_path', $request->headers->get('referer'));
        //}

        return $this->render('global/login.html.twig', [
            'form' => $form->createView(),
            'error' => $error
        ]);
    }


    /**
     * @Route("/account-blocked", name="account-blocked")
     * @param UserInterface $user
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    public function indexAction(UserInterface $user): Response
    {
        dd("BLOCKED!");

        return $this->render('base.html.twig', [
            // 'orderList' => $orderList
        ]);
    }


    /**
     * @Route("/send-password-recovery", name="account-password-send-recovery")
     *
     * @param Request $request
     * @param PasswordRecoveryService $service
     * @param Translator $translator
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function sendPasswordRecoveryMailAction(Request $request, PasswordRecoveryService $service, Translator $translator)
    {
        if ($request->isMethod(Request::METHOD_POST)) {
            try {
                $service->sendRecoveryMail(
                    $request->get('email', ''),
                    $this->document->getProperty('password_reset_mail')
                );

                $this->addFlash('success', $translator->trans('account.reset-mail-sent-when-possible'));
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }

            return $this->redirectToRoute('account-login', ['no-referer-redirect' => true]);
        }

        return $this->render('account/send_password_recovery_mail.html.twig', [
            'hideBreadcrumbs' => true,
            'emailPrefill' => $request->get('email')
        ]);
    }


    /**
     * @Route("/reset-password", name="account-reset-password")
     *
     * @param Request $request
     * @param PasswordRecoveryService $service
     * @param Translator $translator
     *
     * @return Response|RedirectResponse
     */
    public function resetPasswordAction(Request $request, PasswordRecoveryService $service, Translator $translator)
    {
        $token = $request->get('token');
        $customer = $service->getCustomerByToken($token);
        $error = null;
        try {
            if (!$customer) {
                throw new NotFoundHttpException('Invalid token');
            }

            if ($request->isMethod(Request::METHOD_POST)) {

                $newPassword = $request->get('password');

                $this->checkPassword($newPassword);

                $service->setPassword($token, $newPassword);

                $this->addFlash('success', $translator->trans('account.password-reset-successful'));

                return $this->redirectToRoute('account-login', ['no-referer-redirect' => true]);

            }

        } catch (\Exception $exception) {
            $error = $exception->getMessage();
        }

        return $this->render('account/reset_password.html.twig', [
            'hideBreadcrumbs' => true,
            'token' => $token,
            'email' => $customer?->getEmail(),
            'error' => $error
        ]);
    }
}
