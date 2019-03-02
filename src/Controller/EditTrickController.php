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
use App\Handler\FormHandler\EditTrickFormHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EditTrickController extends AbstractController
{
    /**
     * @param $slug
     * @param Request                $request
     * @param EntityManagerInterface $em
     * @param EditTrickFormHandler   $formHandler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/trick/edit/{slug}", name="app_edit_trick")
     */
    public function index($slug, Request $request, EntityManagerInterface $em, EditTrickFormHandler $formHandler)
    {
        if (null === $trick = $em->getRepository(Trick::class)->findOneBy(['slug' => $slug])) {
            throw $this->createNotFoundException('Aucune figure trouvée avec le slug '.$slug);
        }

        $trickDTO = CreateTrickDTO::createFromTrick($trick);

        $form = $this->createForm(TrickFormType::class, $trickDTO);
        $form->handleRequest($request);

        if (($response = $formHandler->handle($form, $trick)) instanceof RedirectResponse) {
            return $response;
        }

        return $this->render('trick/tricks-form.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }
}
