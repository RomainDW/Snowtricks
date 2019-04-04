<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/15/19
 * Time: 10:00 PM.
 */

namespace App\Action;

use App\Action\Interfaces\ForgotPasswordInterface;
use App\Form\ForgotPasswordFormType;
use App\Handler\FormHandler\Interfaces\ForgotPasswordFormHandlerInterface;
use App\Responder\Interfaces\TwigResponderInterface;
use Exception;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForgotPasswordAction implements ForgotPasswordInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var ForgotPasswordFormHandlerInterface
     */
    private $formHandler;

    /**
     * ForgotPasswordAction constructor.
     *
     * @param FormFactoryInterface               $formFactory
     * @param ForgotPasswordFormHandlerInterface $formHandler
     */
    public function __construct(FormFactoryInterface $formFactory, ForgotPasswordFormHandlerInterface $formHandler)
    {
        $this->formFactory = $formFactory;
        $this->formHandler = $formHandler;
    }

    /**
     * @Route("/forgot-password", name="app_forgot_password")
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
        $form = $this->formFactory->create(ForgotPasswordFormType::class);
        $form->handleRequest($request);

        if ($this->formHandler->handle($form)) {
            return $responder('app_forgot_password');
        }

        return $responder('security/forgot-password.html.twig', ['form' => $form->createView()]);
    }
}
