<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/10/19
 * Time: 12:51 PM.
 */

namespace App\Action;

use App\Domain\Entity\Comment;
use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use App\Domain\Manager\CommentManager;
use App\Form\CommentFormType;
use App\Handler\FormHandler\CommentFormHandler;
use App\Repository\CommentRepository;
use App\Responder\CommentRedirectResponder;
use App\Responder\ShowTrickResponder;
use App\Service\SnowtrickConfig;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ShowTrickAction
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
     * @var ShowTrickResponder
     */
    private $responder;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var CommentManager
     */
    private $commentManager;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;
    /**
     * @var CommentRedirectResponder
     */
    private $commentRedirectResponder;

    /**
     * ShowTrickAction constructor.
     *
     * @param Security                 $security
     * @param CommentRepository        $commentRepository
     * @param ShowTrickResponder       $responder
     * @param FormFactoryInterface     $formFactory
     * @param CommentManager           $commentManager
     * @param FlashBagInterface        $flashBag
     * @param CommentRedirectResponder $commentRedirectResponder
     */
    public function __construct(
        Security $security,
        CommentRepository $commentRepository,
        ShowTrickResponder $responder,
        FormFactoryInterface $formFactory,
        CommentManager $commentManager,
        FlashBagInterface $flashBag,
        CommentRedirectResponder $commentRedirectResponder
    ) {
        $this->security = $security;
        $this->commentRepository = $commentRepository;
        $this->responder = $responder;
        $this->formFactory = $formFactory;
        $this->commentManager = $commentManager;
        $this->flashBag = $flashBag;
        $this->commentRedirectResponder = $commentRedirectResponder;
    }

    /**
     * @Route("/trick/show/{slug}", name="app_show_trick")
     *
     * @param Trick              $trick
     * @param Request            $request
     * @param CommentFormHandler $formHandler
     *
     * @return bool|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function __invoke(Trick $trick, Request $request, CommentFormHandler $formHandler)
    {
        $comment = new Comment();

        /** @var User $user */
        $user = $this->security->getUser();
        $form = $this->formFactory->create(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($user && $this->security->isGranted('ROLE_USER') && ($formHandler->handle($form, $comment, $user, $trick))) {
            $this->commentManager->save($comment);
            $this->flashBag->add('success', 'Le commentaire a bien été ajouté !');

            $responder = $this->commentRedirectResponder;

            return $responder($trick);
        }

        $numberOfResults = SnowtrickConfig::getNumberOfCommentsDisplayed();

        $comments = $this->commentRepository->getCommentsPagination(0, $numberOfResults, $trick);

        $totalComments = $this->commentRepository->getNumberOfTotalComments($trick);

        $responder = $this->responder;

        return $responder([
            'trick' => $trick,
            'form' => $form->createView(),
            'comments' => $comments,
            'totalComments' => $totalComments,
            'number_of_results' => $numberOfResults,
        ]);
    }
}
