<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/24/19
 * Time: 2:54 PM.
 */

namespace App\Action;

use App\Domain\Manager\UserManager;
use App\DTO\UpdateUserDTO;
use App\Domain\Entity\User;
use App\Form\UserUpdateFormType;
use App\Handler\FormHandler\AccountFormHandler;
use App\Responder\AccountResponder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AccountAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var AccountResponder
     */
    private $responder;

    /**
     * @var Security
     */
    private $security;

    /**
     * @var AccountFormHandler
     */
    private $formHandler;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * AccountAction constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param AccountResponder     $responder
     * @param Security             $security
     * @param AccountFormHandler   $formHandler
     * @param UserManager          $userManager
     */
    public function __construct(FormFactoryInterface $formFactory, AccountResponder $responder, Security $security, AccountFormHandler $formHandler, UserManager $userManager)
    {
        $this->formFactory = $formFactory;
        $this->responder = $responder;
        $this->security = $security;
        $this->formHandler = $formHandler;
        $this->userManager = $userManager;
    }

    /**
     * @Route("/my-account", name="app_account")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \App\Domain\Exception\ValidationException
     */
    public function __invoke(Request $request)
    {
        $responder = $this->responder;

        /** @var User $user */
        $user = $this->security->getUser();

        $userDTO = UpdateUserDTO::createFromUser($user);

        $form = $this->formFactory->create(UserUpdateFormType::class, $userDTO);
        $form->handleRequest($request);

        if ($this->formHandler->handle($form, $user)) {
            $this->userManager->update($user);

            return $responder([], 'redirect');
        }

        return $responder(['form' => $form->createView()]);
    }
}
