<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/24/19
 * Time: 2:54 PM.
 */

namespace App\Controller;

use App\DTO\UpdateUserDTO;
use App\Form\UserUpdateFormType;
use App\Handler\FormHandler\AccountFormHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/my-account", name="app_account")
     *
     * @param Request            $request
     * @param AccountFormHandler $formHandler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, AccountFormHandler $formHandler)
    {
        $user = $this->getUser();
        $userDTO = UpdateUserDTO::createFromUser($user);

        $form = $this->createForm(UserUpdateFormType::class, $userDTO);
        $form->handleRequest($request);

        if (($response = $formHandler->handle($form, $user)) instanceof RedirectResponse) {
            return $response;
        }

        return $this->render('account/my-account.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
