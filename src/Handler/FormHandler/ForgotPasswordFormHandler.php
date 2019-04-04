<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 3/4/19
 * Time: 6:55 PM.
 */

namespace App\Handler\FormHandler;

use App\Domain\Service\Interfaces\UserServiceInterface;
use App\Handler\FormHandler\Interfaces\ForgotPasswordFormHandlerInterface;
use Exception;
use Symfony\Component\Form\FormInterface;

class ForgotPasswordFormHandler implements ForgotPasswordFormHandlerInterface
{
    private $userService;

    /**
     * ForgotPasswordFormHandler constructor.
     *
     * @param UserServiceInterface $userService
     */
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param FormInterface $form
     *
     * @return bool
     *
     * @throws Exception
     */
    public function handle(FormInterface $form): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->forgotPassword($form->get('email')->getData());

            return true;
        }

        return false;
    }
}
