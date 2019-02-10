<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/9/19
 * Time: 7:14 PM.
 */

namespace App\Controller;

use App\Entity\Trick;
use App\Event\ImageRemoveEvent;
use App\EventSubscriber\ImageUploadSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DeleteTrickController extends AbstractController
{
    /**
     * @Route("/trick/delete/{slug}", name="app_delete_trick")
     *
     * @param Request $request
     * @param $slug
     * @param EntityManagerInterface $em
     *
     * @return RedirectResponse
     */
    public function delete(Request $request, $slug, EntityManagerInterface $em)
    {
        if ($request->isMethod('POST')) {
            if (null === $trick = $em->getRepository(Trick::class)->findOneBy(['slug' => $slug])) {
                throw $this->createNotFoundException('Aucune figure trouvée avec le slug '.$slug);
            }

            $dispatcher = new EventDispatcher();
            $subscriber = new ImageUploadSubscriber();
            $dispatcher->addSubscriber($subscriber);

            foreach ($trick->getImages() as $image) {
                $imageRemoveEvent = new ImageRemoveEvent($image);
                $dispatcher->dispatch(ImageRemoveEvent::NAME, $imageRemoveEvent);
            }

            $em->remove($trick);
            $em->flush();

            $this->addFlash('success', 'la figure '.$trick->getTitle().' a bien été supprimée.');
            $referer = $request->headers->get('referer');

            return new RedirectResponse($referer);
        } else {
            throw $this->createNotFoundException("Cette page n'existe pas.");
        }
    }
}
