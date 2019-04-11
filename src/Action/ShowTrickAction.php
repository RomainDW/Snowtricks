<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/10/19
 * Time: 12:51 PM.
 */

namespace App\Action;

use App\Action\Interfaces\ShowTrickActionInterface;
use App\Domain\Entity\Comment;
use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use App\Form\CommentFormType;
use App\Handler\FormHandler\Interfaces\CommentFormHandlerInterface;
use App\Repository\CommentRepository;
use App\Responder\Interfaces\TwigResponderInterface;
use App\Utils\SnowtrickConfig;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ShowTrickAction implements ShowTrickActionInterface
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var CommentRepository
     */
    private $commentRepository;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;
    /**
     * @var CommentFormHandlerInterface
     */
    private $formHandler;

    /**
     * ShowTrickAction constructor.
     *
     * @param Security                    $security
     * @param CommentRepository           $commentRepository
     * @param FormFactoryInterface        $formFactory
     * @param FlashBagInterface           $flashBag
     * @param CommentFormHandlerInterface $formHandler
     */
    public function __construct(
        Security $security,
        CommentRepository $commentRepository,
        FormFactoryInterface $formFactory,
        FlashBagInterface $flashBag,
        CommentFormHandlerInterface $formHandler
    ) {
        $this->security = $security;
        $this->commentRepository = $commentRepository;
        $this->formFactory = $formFactory;
        $this->flashBag = $flashBag;
        $this->formHandler = $formHandler;
    }

    /**
     * @Route("/figure/voir/{slug}", name="app_show_trick")
     *
     * @param Trick                  $trick
     * @param Request                $request
     * @param TwigResponderInterface $responder
     *
     * @return bool|RedirectResponse|Response
     *
     * @throws NonUniqueResultException
     */
    public function __invoke(Trick $trick, Request $request, TwigResponderInterface $responder)
    {
        $comment = new Comment();

        /** @var User $user */
        $user = $this->security->getUser();
        $form = $this->formFactory->create(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($user && $this->security->isGranted('ROLE_USER') && ($this->formHandler->handle($form, $comment, $user, $trick))) {
            $this->flashBag->add('success', 'Le commentaire a bien été ajouté !');

            return $responder('app_show_trick', ['slug' => $trick->getSlug(), '_fragment' => 'comments']);
        }

        $numberOfResults = SnowtrickConfig::getNumberOfCommentsDisplayed();

        $comments = $this->commentRepository->getCommentsPagination(0, $numberOfResults, $trick);

        $totalComments = $this->commentRepository->getNumberOfTotalComments($trick);

        return $responder('trick/show.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'comments' => $comments,
            'totalComments' => $totalComments,
            'number_of_results' => $numberOfResults,
        ]);
    }
}
