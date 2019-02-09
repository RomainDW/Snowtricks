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
use App\EventSubscriber\ImageUploadSubscriber;
use App\Form\TrickFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CreateTrickController extends AbstractController
{
    /**
     * @param Request                $request
     * @param EntityManagerInterface $entityManager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     * @Route("/trick/add", name="app_create_trick")
     */
    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        $trick = new Trick();
        $form = $this->createForm(TrickFormType::class, $trick);
        $form->handleRequest($request);

        $dispatcher = new EventDispatcher();
        $subscriber = new ImageUploadSubscriber();
        $dispatcher->addSubscriber($subscriber);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setCreatedAt(new \DateTime());
            $trick->setUser($this->getUser());

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
