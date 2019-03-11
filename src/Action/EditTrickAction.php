<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/6/19
 * Time: 7:26 PM.
 */

namespace App\Action;

use App\Domain\Manager\TrickManager;
use App\DTO\CreateTrickDTO;
use App\Domain\Entity\Trick;
use App\Form\TrickFormType;
use App\Handler\FormHandler\EditTrickFormHandler;
use App\Responder\EditTrickResponder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EditTrickAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var EditTrickResponder
     */
    private $responder;
    /**
     * @var TrickManager
     */
    private $trickManager;

    /**
     * EditTrickAction constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param EditTrickResponder   $responder
     * @param TrickManager         $trickManager
     */
    public function __construct(FormFactoryInterface $formFactory, EditTrickResponder $responder, TrickManager $trickManager)
    {
        $this->formFactory = $formFactory;
        $this->responder = $responder;
        $this->trickManager = $trickManager;
    }

    /**
     * @Route("/trick/edit/{slug}", name="app_edit_trick")
     *
     * @param Trick                $trick
     * @param Request              $request
     * @param EditTrickFormHandler $formHandler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \App\Domain\Exception\ValidationException
     * @throws \Exception
     */
    public function __invoke(Trick $trick, Request $request, EditTrickFormHandler $formHandler)
    {
        $responder = $this->responder;
        $trickDTO = CreateTrickDTO::createFromTrick($trick);

        $form = $this->formFactory->create(TrickFormType::class, $trickDTO);
        $form->handleRequest($request);

        if ($formHandler->handle($form, $trick)) {
            $this->trickManager->save($trick, 'update');

            $responder(['slug' => $trick->getSlug()], 'redirect');
        }

        return $responder(['trick' => $trick, 'form' => $form->createView()]);
    }
}
