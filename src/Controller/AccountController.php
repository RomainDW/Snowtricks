<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/24/19
 * Time: 2:54 PM.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/my-account", name="app_account")
     */
    public function index()
    {
        return $this->render('trick/tricks-form.html.twig', [
//            'form' => $form->createView(),
        ]);
    }
}
