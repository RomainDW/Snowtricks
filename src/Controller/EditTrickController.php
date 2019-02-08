<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/6/19
 * Time: 7:26 PM.
 */

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickFormType;
use Doctrine\Common\Collections\ArrayCollection;
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
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/trick/edit/{slug}", name="app_edit_trick")
     *
     * @throws \Exception
     */
    public function index($slug, Request $request, EntityManagerInterface $em)
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

            // remove the relationship between the image and the Trick
            foreach ($originalImages as $image) {
                // remove old images that has been removed
                if (false === $trick->getImages()->contains($image)) {
                    // remove the Trick from the Image
                    $trick->removeImage($image);
                    $em->remove($image);
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
