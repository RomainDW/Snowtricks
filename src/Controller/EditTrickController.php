<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/6/19
 * Time: 7:26 PM.
 */

namespace App\Controller;

use App\DTO\CreateTrickDTO;
use App\Entity\Trick;
use App\Form\TrickFormType;
use App\Service\TrickService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EditTrickController extends AbstractController
{
    /**
     * @param $slug
     * @param Request                $request
     * @param EntityManagerInterface $em
     * @param TrickService           $trickService
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     * @Route("/trick/edit/{slug}", name="app_edit_trick")
     */
    public function index($slug, Request $request, EntityManagerInterface $em, TrickService $trickService)
    {
        if (null === $trick = $em->getRepository(Trick::class)->findOneBy(['slug' => $slug])) {
            throw $this->createNotFoundException('Aucune figure trouvée avec le slug '.$slug);
        }

        $trickDTO = CreateTrickDTO::createFromTrick($trick);

        $form = $this->createForm(TrickFormType::class, $trickDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updatedTrickDTO = $trickService->UpdateTrick($trick, $form->getData());

            $trick->updateFromDTO($updatedTrickDTO);

            $em->persist($trick);
            $em->flush();

            $this->addFlash('success', 'La figure a bien été modifiée !');

            return $this->redirectToRoute('app_edit_trick', ['slug' => $trick->getSlug()]);
        }

        return $this->render('trick/tricks-form.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }
}
