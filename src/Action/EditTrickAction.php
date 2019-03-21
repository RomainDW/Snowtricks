<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/6/19
 * Time: 7:26 PM.
 */

namespace App\Action;

use App\DTO\CreateTrickDTO;
use App\Domain\Entity\Trick;
use App\Form\TrickFormType;
use App\Handler\FormHandler\EditTrickFormHandler;
use App\Responder\EditTrickResponder;
use App\Domain\Service\TrickService;
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
     * @var TrickService
     */
    private $trickService;
    /**
     * @var EditTrickFormHandler
     */
    private $formHandler;

    /**
     * EditTrickAction constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param TrickService         $trickService
     * @param EditTrickFormHandler $formHandler
     */
    public function __construct(FormFactoryInterface $formFactory, TrickService $trickService, EditTrickFormHandler $formHandler)
    {
        $this->formFactory = $formFactory;
        $this->trickService = $trickService;
        $this->formHandler = $formHandler;
    }

    /**
     * @Route("/trick/edit/{slug}", name="app_edit_trick")
     *
     * @param Trick              $trick
     * @param Request            $request
     * @param EditTrickResponder $responder
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Loader
     * @throws \Exception
     */
    public function __invoke(Trick $trick, Request $request, EditTrickResponder $responder)
    {
        $trickDTO = CreateTrickDTO::createFromTrick($trick);

        $form = $this->formFactory->create(TrickFormType::class, $trickDTO);
        $form->handleRequest($request);

        if ($this->formHandler->handle($form, $trick)) {

            $responder(['slug' => $trick->getSlug()], 'redirect');
        }

        return $responder(['trick' => $trick, 'form' => $form->createView()]);
    }
}
