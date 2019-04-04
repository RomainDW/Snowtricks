<?php

namespace App\Action;

use App\Action\Interfaces\RegistrationActionInterface;
use App\Domain\Service\Interfaces\UserServiceInterface;
use App\Form\RegistrationFormType;
use App\Handler\FormHandler\Interfaces\RegistrationFormHandlerInterface;
use App\Responder\Interfaces\TwigResponderInterface;
use Exception;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class RegistrationAction implements RegistrationActionInterface
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
     * @var UserServiceInterface
     */
    private $userService;
    /**
     * @var RegistrationFormHandlerInterface
     */
    private $formHandler;

    /**
     * RegistrationAction constructor.
     *
     * @param FormFactoryInterface             $formFactory
     * @param Security                         $security
     * @param FlashBagInterface                $flashBag
     * @param UserServiceInterface             $userService
     * @param RegistrationFormHandlerInterface $formHandler
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        Security $security,
        FlashBagInterface $flashBag,
        UserServiceInterface $userService,
        RegistrationFormHandlerInterface $formHandler
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
     * @param Request                $request
     * @param TwigResponderInterface $responder
     *
     * @return Response
     *
     * @throws Exception
     */
    public function __invoke(Request $request, TwigResponderInterface $responder)
    {
        if ($this->security->isGranted('ROLE_USER')) {
            $this->flashBag->add('error', 'Vous êtes déjà connecté(e)');

            return $responder('homepage');
        }

        $form = $this->formFactory->create(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($this->formHandler->handle($form)) {
            return $responder('app_mail_sent', ['id' => $this->userService->getUserId()]);
        }

        return $responder('registration/register.html.twig', ['registrationForm' => $form->createView()]);
    }
}
