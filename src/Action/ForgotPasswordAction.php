<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/15/19
 * Time: 10:00 PM.
 */

namespace App\Action;

use App\Form\ForgotPasswordFormType;
use App\Handler\FormHandler\ForgotPasswordFormHandler;
use App\Domain\Service\UserService;
use App\Responder\ForgotPasswordResponder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ForgotPasswordAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var ForgotPasswordFormHandler
     */
    private $formHandler;

    /**
     * ForgotPasswordAction constructor.
     *
     * @param FormFactoryInterface      $formFactory
     * @param UserService               $userService
     * @param ForgotPasswordFormHandler $formHandler
     */
    public function __construct(FormFactoryInterface $formFactory, UserService $userService, ForgotPasswordFormHandler $formHandler)
    {
        $this->formFactory = $formFactory;
        $this->userService = $userService;
        $this->formHandler = $formHandler;
    }

    /**
     * @Route("/forgot-password", name="app_forgot_password")
     *
     * @param Request                 $request
     * @param ForgotPasswordResponder $responder
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function __invoke(Request $request, ForgotPasswordResponder $responder)
    {
        $form = $this->formFactory->create(ForgotPasswordFormType::class);
        $form->handleRequest($request);

        if ($this->formHandler->handle($form)) {

            return $responder([], 'redirect');
        }

        return $responder(['form' => $form->createView()]);
    }
}
