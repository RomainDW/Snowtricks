<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/6/19
 * Time: 7:26 PM.
 */

namespace App\Controller;

use App\Entity\Trick;
use App\Event\ImageRemoveEvent;
use App\Event\ImageUploadEvent;
use App\Form\TrickFormType;
use App\Service\SlugService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EditTrickController extends AbstractController
{
    /**
     * @param $slug
     * @param Request                  $request
     * @param EntityManagerInterface   $em
     * @param EventDispatcherInterface $dispatcher
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     * @Route("/trick/edit/{slug}", name="app_edit_trick")
     */
    public function index($slug, Request $request, EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        if (null === $trick = $em->getRepository(Trick::class)->findOneBy(['slug' => $slug])) {
            throw $this->createNotFoundException('Aucune figure trouvée avec le slug '.$slug);
        }

        $originalImages = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($trick->getImages() as $image) {
            $originalImages->add($image);
        }

        $form = $this->createForm(TrickFormType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setUpdatedAt(new \DateTime());
            $slug = SlugService::slugify($form->get('title')->getData());
            $trick->setSlug($slug);

            foreach ($trick->getImages() as $image) {
                if (!empty($image->getFile() && !empty($image->getFileName()))) {
                    $imageRemoveEvent = new ImageRemoveEvent($image);
                    $dispatcher->dispatch(ImageRemoveEvent::NAME, $imageRemoveEvent);
                }
                if (!empty($image->getFile())) {
                    $imageUploadEvent = new ImageUploadEvent($image);
                    $dispatcher->dispatch(ImageUploadEvent::NAME, $imageUploadEvent);
                }
            }

            foreach ($originalImages as $image) {
                // remove old images that has been removed
                if (false === $trick->getImages()->contains($image)) {
                    // remove the Image from the Trick
                    $trick->getImages()->removeElement($image);
                    $em->remove($image);
                    $event = new ImageRemoveEvent($image);
                    $dispatcher->dispatch(ImageRemoveEvent::NAME, $event);
                }
            }

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
