<?php

namespace App\Action;

use App\Domain\Entity\User;
use App\Domain\Manager\UserManager;
use App\Form\RegistrationFormType;
use App\Handler\FormHandler\RegistrationFormHandler;
use App\Responder\HomeRedirectResponder;
use App\Responder\MailSentRedirectResponder;
use App\Responder\RegistrationResponder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class RegistrationAction
{
    /**
     * @var RegistrationResponder
     */
    private $responder;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var Security
     */
    private $security;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var HomeRedirectResponder
     */
    private $homeRedirectResponder;
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var MailSentRedirectResponder
     */
    private $mailSentRedirectResponder;

    /**
     * RegistrationAction constructor.
     *
     * @param RegistrationResponder     $responder
     * @param FormFactoryInterface      $formFactory
     * @param Security                  $security
     * @param FlashBagInterface         $flashBag
     * @param HomeRedirectResponder     $homeRedirectResponder
     * @param UserManager               $userManager
     * @param MailSentRedirectResponder $mailSentRedirectResponder
     */
    public function __construct(
        RegistrationResponder $responder,
        FormFactoryInterface $formFactory,
        Security $security,
        FlashBagInterface $flashBag,
        HomeRedirectResponder $homeRedirectResponder,
        UserManager $userManager,
        MailSentRedirectResponder $mailSentRedirectResponder
    ) {
        $this->responder = $responder;
        $this->formFactory = $formFactory;
        $this->security = $security;
        $this->flashBag = $flashBag;
        $this->homeRedirectResponder = $homeRedirectResponder;
        $this->userManager = $userManager;
        $this->mailSentRedirectResponder = $mailSentRedirectResponder;
    }

    /**
     * @Route("/register", name="app_register")
     *
     * @param Request                 $request
     * @param RegistrationFormHandler $formHandler
     *
     * @return Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \App\Domain\Exception\ValidationException
     * @throws \Exception
     */
    public function __invoke(Request $request, RegistrationFormHandler $formHandler)
    {
        $homeRedirectResponder = $this->homeRedirectResponder;
        $responder = $this->responder;

        if ($this->security->isGranted('ROLE_USER')) {
            $this->flashBag->add('error', 'Vous êtes déjà connecté(e)');

            return $homeRedirectResponder();
        }

        $form = $this->formFactory->create(RegistrationFormType::class);
        $form->handleRequest($request);

        if (($user = $formHandler->handle($form)) instanceof User) {
            $this->userManager->register($user);
            $responder = $this->mailSentRedirectResponder;

            return $responder($user);
        }

        return $responder(['registrationForm' => $form->createView()]);
    }
}
