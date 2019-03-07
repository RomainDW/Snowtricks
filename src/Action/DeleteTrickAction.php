<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/9/19
 * Time: 7:14 PM.
 */

namespace App\Action;

use App\Entity\Trick;
use App\Event\ImageRemoveEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DeleteTrickAction extends AbstractController
{
    /**
     * @Route("/trick/delete/{slug}", methods={"POST"}, name="app_delete_trick")
     *
     * @param $slug
     * @param EntityManagerInterface   $em
     * @param EventDispatcherInterface $dispatcher
     *
     * @return RedirectResponse
     */
    public function delete($slug, EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        if (null === $trick = $em->getRepository(Trick::class)->findOneBy(['slug' => $slug])) {
            throw $this->createNotFoundException('Aucune figure trouvée avec le slug '.$slug);
        }

        foreach ($trick->getImages() as $image) {
            $imageRemoveEvent = new ImageRemoveEvent($image);
            $dispatcher->dispatch(ImageRemoveEvent::NAME, $imageRemoveEvent);
        }

        $em->remove($trick);
        $em->flush();

        $this->addFlash('success', 'la figure '.$trick->getTitle().' a bien été supprimée.');

        return $this->redirectToRoute('homepage');
    }
}
