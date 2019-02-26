<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/2/19
 * Time: 12:00 PM.
 */

namespace App\Controller;

use App\Form\TrickFormType;
use App\Service\TrickService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CreateTrickController extends AbstractController
{
    /**
     * @param Request                $request
     * @param EntityManagerInterface $entityManager
     * @param TrickService           $trickService
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     * @Route("/trick/add", name="app_create_trick")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, TrickService $trickService)
    {
        $form = $this->createForm(TrickFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick = $trickService->InitTrick($form->getData(), $this->getUser());

            $entityManager->persist($trick);
            $entityManager->flush();

            $this->addFlash('success', 'La figure a bien été ajoutée !');

            return $this->redirectToRoute('app_show_trick', ['slug' => $trick->getSlug()]);
        }

        return $this->render('trick/tricks-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
