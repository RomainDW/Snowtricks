<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/24/19
 * Time: 2:54 PM.
 */

namespace App\Action;

use App\DTO\UpdateUserDTO;
use App\Domain\Entity\User;
use App\Form\UserUpdateFormType;
use App\Handler\FormHandler\AccountFormHandler;
use App\Responder\AccountResponder;
use App\Domain\Service\UserService;
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
     * @var Security
     */
    private $security;

    /**
     * @var AccountFormHandler
     */
    private $formHandler;
    /**
     * @var UserService
     */
    private $userService;

    /**
     * AccountAction constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param Security             $security
     * @param AccountFormHandler   $formHandler
     * @param UserService          $userService
     */
    public function __construct(FormFactoryInterface $formFactory, Security $security, AccountFormHandler $formHandler, UserService $userService)
    {
        $this->formFactory = $formFactory;
        $this->security = $security;
        $this->formHandler = $formHandler;
        $this->userService = $userService;
    }

    /**
     * @Route("/my-account", name="app_account")
     *
     * @param Request          $request
     * @param AccountResponder $responder
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \App\Domain\Exception\ValidationException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(Request $request, AccountResponder $responder)
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $userDTO = UpdateUserDTO::createFromUser($user);

        $form = $this->formFactory->create(UserUpdateFormType::class, $userDTO);
        $form->handleRequest($request);

        if ($this->formHandler->handle($form, $user)) {
            return $responder([], 'redirect');
        }

        return $responder(['form' => $form->createView()]);
    }
}
