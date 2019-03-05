<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/2/19
 * Time: 12:00 PM.
 */

namespace App\Controller;

use App\Form\TrickFormType;
use App\Handler\FormHandler\CreateTrickFormHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CreateTrickController extends AbstractController
{
    /**
     * @param Request                $request
     * @param CreateTrickFormHandler $formHandler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/trick/add", name="app_create_trick")
     */
    public function index(Request $request, CreateTrickFormHandler $formHandler)
    {
        $form = $this->createForm(TrickFormType::class);
        $form->handleRequest($request);

        if (($response = $formHandler->handle($form, $this->getUser())) instanceof RedirectResponse) {
            return $response;
        }

        return $this->render('trick/tricks-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
