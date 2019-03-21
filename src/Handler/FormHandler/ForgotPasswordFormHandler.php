<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 3/4/19
 * Time: 6:55 PM.
 */

namespace App\Handler\FormHandler;

use App\Domain\Service\UserService;
use Symfony\Component\Form\FormInterface;

class ForgotPasswordFormHandler
{
    private $userService;

    /**
     * ForgotPasswordFormHandler constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param FormInterface $form
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function handle(FormInterface $form)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->forgotPassword($form->get('email')->getData());

            return true;
        }

        return false;
    }
}
