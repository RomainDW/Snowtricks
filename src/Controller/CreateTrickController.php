<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/2/19
 * Time: 12:00 PM.
 */

namespace App\Controller;

use App\Entity\Trick;
use App\Event\ImageUploadEvent;
use App\Form\TrickFormType;
use App\Service\SlugService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CreateTrickController extends AbstractController
{
    /**
     * @param Request                  $request
     * @param EntityManagerInterface   $entityManager
     * @param EventDispatcherInterface $dispatcher
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     * @Route("/trick/add", name="app_create_trick")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher)
    {
        $trick = new Trick();
        $form = $this->createForm(TrickFormType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setCreatedAt(new \DateTime());
            $trick->setUser($this->getUser());
            $slug = SlugService::slugify($form->get('title')->getData());
            $trick->setSlug($slug);

            foreach ($trick->getImages() as $image) {
                $event = new ImageUploadEvent($image);
                $dispatcher->dispatch(ImageUploadEvent::NAME, $event);
            }

            $entityManager->persist($trick);
            $entityManager->flush();

            $this->addFlash('success', 'La figure a bien été ajoutée !');

            //TODO: redirect to the trick
            return $this->redirectToRoute('app_create_trick');
        }

        return $this->render('trick/tricks-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
