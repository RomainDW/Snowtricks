<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/2/19
 * Time: 12:00 PM.
 */

namespace App\Action;

use App\Domain\Manager\TrickManager;
use App\Form\TrickFormType;
use App\Handler\FormHandler\CreateTrickFormHandler;
use App\Responder\CreateTrickResponder;
use App\Service\TrickService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CreateTrickAction
{
    /**
     * @var CreateTrickResponder
     */
    private $responder;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var Security
     */
    private $security;

    /**
     * @var TrickService
     */
    private $trickService;

    /**
     * @var TrickManager
     */
    private $trickManager;

    /**
     * @var CreateTrickFormHandler
     */
    private $formHandler;

    /**
     * CreateTrickAction constructor.
     *
     * @param CreateTrickResponder   $responder
     * @param FormFactoryInterface   $formFactory
     * @param Security               $security
     * @param TrickManager           $trickManager
     * @param TrickService           $trickService
     * @param CreateTrickFormHandler $formHandler
     */
    public function __construct(CreateTrickResponder $responder, FormFactoryInterface $formFactory, Security $security, TrickManager $trickManager, TrickService $trickService, CreateTrickFormHandler $formHandler)
    {
        $this->responder = $responder;
        $this->formFactory = $formFactory;
        $this->security = $security;
        $this->trickService = $trickService;
        $this->trickManager = $trickManager;
        $this->formHandler = $formHandler;
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     * @Route("/trick/add", name="app_create_trick")
     */
    public function __invoke(Request $request)
    {
        $responder = $this->responder;
        $form = $this->formFactory->create(TrickFormType::class);
        $form->handleRequest($request);

        if ($trick = $this->formHandler->handle($form, $this->security->getUser())) {
            $this->trickManager->save($trick);

            return $responder(['slug' => $trick->getSlug()], 'redirect');
        }

        return $responder(['form' => $form->createView()]);
    }
}
