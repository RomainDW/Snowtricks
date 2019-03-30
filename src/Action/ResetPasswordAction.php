<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 6:42 PM.
 */

namespace App\Action;

use App\Domain\Entity\User;
use App\Form\ResetPasswordFormType;
use App\Handler\FormHandler\ResetPasswordFormHandler;
use App\Responder\Interfaces\TwigResponderInterface;
use App\Domain\Service\UserService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @var FlashBagInterface
     */
    private $flashBag;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var ResetPasswordFormHandler
     */
    private $formHandler;

    /**
     * ResetPasswordAction constructor.
     *
     * @param FormFactoryInterface     $formFactory
     * @param FlashBagInterface        $flashBag
     * @param Security                 $security
     * @param ResetPasswordFormHandler $formHandler
     * @param UserService              $userService
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        FlashBagInterface $flashBag,
        Security $security,
        ResetPasswordFormHandler $formHandler,
        UserService $userService
    ) {
        $this->formFactory = $formFactory;
        $this->flashBag = $flashBag;
        $this->security = $security;
        $this->userService = $userService;
        $this->formHandler = $formHandler;
    }

    /**
     * @Route("/reset-password/{vkey}", name="app_reset_password")
     *
     * @param User                   $user
     * @param Request                $request
     * @param TwigResponderInterface $responder
     *
     * @return bool|RedirectResponse|Response*
     */
    public function __invoke(User $user, Request $request, TwigResponderInterface $responder)
    {
        $form = $this->formFactory->create(ResetPasswordFormType::class);
        $form->handleRequest($request);

        if ($this->formHandler->handle($form, $user)) {
            $this->flashBag->add('success', 'Votre mot de passe a été réinitialisé, vous pouvez maintenant vous connecter');

            if ($this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
                return $responder('app_account');
            } else {
                return $responder('app_login');
            }
        }

        return $responder('security/reset-password.html.twig', ['form' => $form->createView()]);
    }
}
