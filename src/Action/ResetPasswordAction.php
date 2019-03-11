<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 6:42 PM.
 */

namespace App\Action;

use App\Domain\Entity\User;
use App\Domain\Manager\UserManager;
use App\Form\ResetPasswordFormType;
use App\Handler\FormHandler\ResetPasswordFormHandler;
use App\Responder\AccountRedirectResponder;
use App\Responder\LoginRedirectResponder;
use App\Responder\ResetPasswordResponder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ResetPasswordAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var ResetPasswordResponder
     */
    private $responder;
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var AccountRedirectResponder
     */
    private $accountRedirectResponder;
    /**
     * @var LoginRedirectResponder
     */
    private $loginRedirectResponder;

    /**
     * ResetPasswordAction constructor.
     *
     * @param FormFactoryInterface     $formFactory
     * @param ResetPasswordResponder   $responder
     * @param UserManager              $userManager
     * @param FlashBagInterface        $flashBag
     * @param Security                 $security
     * @param AccountRedirectResponder $accountRedirectResponder
     * @param LoginRedirectResponder   $loginRedirectResponder
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        ResetPasswordResponder $responder,
        UserManager $userManager,
        FlashBagInterface $flashBag,
        Security $security,
        AccountRedirectResponder $accountRedirectResponder,
        LoginRedirectResponder $loginRedirectResponder
    ) {
        $this->formFactory = $formFactory;
        $this->responder = $responder;
        $this->userManager = $userManager;
        $this->flashBag = $flashBag;
        $this->security = $security;
        $this->accountRedirectResponder = $accountRedirectResponder;
        $this->loginRedirectResponder = $loginRedirectResponder;
    }

    /**
     * @Route("/reset-password/{vkey}", name="app_reset_password")
     *
     * @param User                     $user
     * @param Request                  $request
     * @param ResetPasswordFormHandler $formHandler
     *
     * @return bool|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \App\Domain\Exception\ValidationException
     */
    public function __invoke(User $user, Request $request, ResetPasswordFormHandler $formHandler)
    {
        $form = $this->formFactory->create(ResetPasswordFormType::class);
        $form->handleRequest($request);

        if ($formHandler->handle($form, $user)) {
            $this->userManager->save($user);
            $this->flashBag->add('success', 'Votre mot de passe a été réinitialisé, vous pouvez maintenant vous connecter');

            if ($this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
                $responder = $this->accountRedirectResponder;
                return $responder();
            } else {
                $responder = $this->loginRedirectResponder;
                return $responder();
            }
        }

        $responder = $this->responder;

        return $responder(['form' => $form->createView()]);
    }
}
