<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/15/19
 * Time: 10:00 PM.
 */

namespace App\Action;

use App\Entity\User;
use App\Form\ForgotPasswordFormType;
use App\Form\ResetPasswordFormType;
use App\Handler\FormHandler\ForgotPasswordFormHandler;
use App\Handler\FormHandler\ResetPasswordFormHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ForgotPasswordAction extends AbstractController
{
    /**
     * @Route("/forgot-password", name="app_forgot_password")
     *
     * @param Request                   $request
     * @param ForgotPasswordFormHandler $formHandler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(Request $request, ForgotPasswordFormHandler $formHandler)
    {
        $form = $this->createForm(ForgotPasswordFormType::class);
        $form->handleRequest($request);

        if (($response = $formHandler->handle($form)) instanceof RedirectResponse) {
            return $response;
        }

        return $this->render('security/forgot-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reset-password/{vkey}", name="app_reset_password")
     *
     * @param User                     $user
     * @param Request                  $request
     * @param ResetPasswordFormHandler $formHandler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function resetPassword(User $user, Request $request, ResetPasswordFormHandler $formHandler)
    {
        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);

        if (($response = $formHandler->handle($form, $user)) instanceof RedirectResponse) {
            return $response;
        }

        return $this->render('security/reset-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
