<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/2/19
 * Time: 12:00 PM.
 */

namespace App\Controller;

use App\Entity\Trick;
use App\Form\AddTrickFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CreateTrickController extends AbstractController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/trick/add", name="app_create_trick")
     */
    public function index(Request $request)
    {
        $trick = new Trick();
        $form = $this->createForm(AddTrickFormType::class, $trick);
        $form->handleRequest($request);

        return $this->render('trick/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
