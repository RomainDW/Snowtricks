<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/15/19
 * Time: 10:00 PM.
 */

namespace App\Action;

use App\Domain\Manager\UserManager;
use App\Form\ForgotPasswordFormType;
use App\Handler\FormHandler\ForgotPasswordFormHandler;
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
     * @var ForgotPasswordResponder
     */
    private $responder;
    /**
     * @var UserManager
     */
    private $manager;

    /**
     * ForgotPasswordAction constructor.
     *
     * @param FormFactoryInterface    $formFactory
     * @param ForgotPasswordResponder $responder
     * @param UserManager             $manager
     */
    public function __construct(FormFactoryInterface $formFactory, ForgotPasswordResponder $responder, UserManager $manager)
    {
        $this->formFactory = $formFactory;
        $this->responder = $responder;
        $this->manager = $manager;
    }

    /**
     * @Route("/forgot-password", name="app_forgot_password")
     *
     * @param Request                   $request
     * @param ForgotPasswordFormHandler $formHandler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function __invoke(Request $request, ForgotPasswordFormHandler $formHandler)
    {
        $responder = $this->responder;
        $form = $this->formFactory->create(ForgotPasswordFormType::class);
        $form->handleRequest($request);

        if (false !== ($user = $formHandler->handle($form))) {
            $this->manager->forgotPassword($user);
            return $responder([], 'redirect');
        }

        return $responder(['form' => $form->createView()]);
    }
}
