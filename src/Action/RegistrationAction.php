<?php

namespace App\Action;

use App\Form\RegistrationFormType;
use App\Handler\FormHandler\RegistrationFormHandler;
use App\Responder\RegistrationResponder;
use App\Domain\Service\UserService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class RegistrationAction
{
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
     * @var UserService
     */
    private $userService;
    /**
     * @var RegistrationFormHandler
     */
    private $formHandler;

    /**
     * RegistrationAction constructor.
     *
     * @param FormFactoryInterface    $formFactory
     * @param Security                $security
     * @param FlashBagInterface       $flashBag
     * @param UserService             $userService
     * @param RegistrationFormHandler $formHandler
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        Security $security,
        FlashBagInterface $flashBag,
        UserService $userService,
        RegistrationFormHandler $formHandler
    ) {
        $this->formFactory = $formFactory;
        $this->security = $security;
        $this->flashBag = $flashBag;
        $this->userService = $userService;
        $this->formHandler = $formHandler;
    }

    /**
     * @Route("/register", name="app_register")
     *
     * @param Request               $request
     * @param RegistrationResponder $responder
     *
     * @return Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function __invoke(Request $request, RegistrationResponder $responder)
    {
        if ($this->security->isGranted('ROLE_USER')) {
            $this->flashBag->add('error', 'Vous êtes déjà connecté(e)');

            return $responder([], 'redirect-homepage');
        }

        $form = $this->formFactory->create(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($this->formHandler->handle($form)) {
            return $responder(['id' => $this->formHandler->getUserId()], 'redirect-mail-sent');
        }

        return $responder(['registrationForm' => $form->createView()]);
    }
}
