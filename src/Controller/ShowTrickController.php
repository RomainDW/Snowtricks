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
use App\Handler\FormHandler\CommentFormHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @param CommentFormHandler     $formHandler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Exception
     * @Route("/trick/show/{slug}", name="app_show_trick")
     */
    public function index($slug, EntityManagerInterface $em, Request $request, CommentFormHandler $formHandler)
    {
        if (null === $trick = $em->getRepository(Trick::class)->findOneBy(['slug' => $slug])) {
            throw $this->createNotFoundException('Aucune figure trouvée avec le slug '.$slug);
        }

        $comment = new Comment();
        $user = $this->getUser();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if (($response = $formHandler->handle($form, $comment, $user, $trick, $slug)) instanceof RedirectResponse) {
            return $response;
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
     * @param EntityManagerInterface $em
     *
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
