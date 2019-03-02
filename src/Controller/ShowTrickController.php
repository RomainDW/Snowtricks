<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/10/19
 * Time: 12:51 PM.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ShowTrickController extends AbstractController
{
    // define how many comments you want per page
    private $number_of_results = 3;

    /**
     * @param $slug
     * @param EntityManagerInterface $em
     * @param Request                $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/trick/show/{slug}", name="app_show_trick")
     *
     * @throws \Exception
     */
    public function index($slug, EntityManagerInterface $em, Request $request)
    {
        if (null === $trick = $em->getRepository(Trick::class)->findOneBy(['slug' => $slug])) {
            throw $this->createNotFoundException('Aucune figure trouvée avec le slug '.$slug);
        }

        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $this->isGranted('ROLE_USER')) {
            $comment->setCreatedAt(new \DateTime());
            $comment->setUser($this->getUser());
            $comment->setTrick($trick);

            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Le commentaire a bien été ajouté !');

            return $this->redirectToRoute('app_show_trick', ['slug' => $slug, '_fragment' => 'comments']);
        }

        $repo = $em->getRepository(Comment::class);

        $comments = $repo->getCommentsPagination(0, $this->number_of_results, $trick);

        $totalComments = $repo->getNumberOfTotalComments($trick);

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'comments' => $comments,
            'totalComments' => $totalComments,
            'number_of_results' => $this->number_of_results,
        ]);
    }

    /**
     * @param $slug
     * @param $offset
     *
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/load-comments/{slug}/{offset}", name="loadComments", methods={"POST"})
     */
    public function loadComments($slug, $offset, EntityManagerInterface $em)
    {
        $trick = $em->getRepository(Trick::class)->findOneBy(['slug' => $slug]);

        $repo = $this->getDoctrine()->getRepository(Comment::class);

        $comments = $repo->getCommentsPagination($offset, $this->number_of_results, $trick);

        return $this->render('trick/_partials/ajax-comments.html.twig', [
            'comments' => $comments,
        ]);
    }
}
