<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/24/19
 * Time: 2:54 PM.
 */

namespace App\Action;

use App\Action\Interfaces\AccountActionInterface;
use App\Domain\DTO\UpdateUserDTO;
use App\Domain\Entity\User;
use App\Form\UserUpdateFormType;
use App\Handler\FormHandler\Interfaces\AccountFormHandlerInterface;
use App\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AccountAction implements AccountActionInterface
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
     * @var AccountFormHandlerInterface
     */
    private $formHandler;

    /**
     * AccountAction constructor.
     *
     * @param FormFactoryInterface        $formFactory
     * @param Security                    $security
     * @param AccountFormHandlerInterface $formHandler
     */
    public function __construct(FormFactoryInterface $formFactory, Security $security, AccountFormHandlerInterface $formHandler)
    {
        $this->formFactory = $formFactory;
        $this->security = $security;
        $this->formHandler = $formHandler;
    }

    /**
     * @Route("/compte", name="app_account")
     *
     * @param Request                $request
     * @param TwigResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(Request $request, TwigResponderInterface $responder)
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $userDTO = UpdateUserDTO::createFromUser($user);

        $form = $this->formFactory->create(UserUpdateFormType::class, $userDTO);
        $form->handleRequest($request);

        if ($this->formHandler->handle($form, $user)) {
            return $responder('app_account');
        }

        return $responder('account/my-account.html.twig', ['form' => $form->createView()]);
    }
}
