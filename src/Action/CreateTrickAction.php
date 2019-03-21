<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/2/19
 * Time: 12:00 PM.
 */

namespace App\Action;

use App\Form\TrickFormType;
use App\Handler\FormHandler\CreateTrickFormHandler;
use App\Responder\CreateTrickResponder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CreateTrickAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var Security
     */
    private $security;

    /**
     * @var CreateTrickFormHandler
     */
    private $formHandler;

    /**
     * CreateTrickAction constructor.
     *
     * @param FormFactoryInterface   $formFactory
     * @param Security               $security
     * @param CreateTrickFormHandler $formHandler
     */
    public function __construct(FormFactoryInterface $formFactory, Security $security, CreateTrickFormHandler $formHandler)
    {
        $this->formFactory = $formFactory;
        $this->security = $security;
        $this->formHandler = $formHandler;
    }

    /**
     * @param Request              $request
     * @param CreateTrickResponder $responder
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     * @Route("/trick/add", name="app_create_trick")
     */
    public function __invoke(Request $request, CreateTrickResponder $responder)
    {
        $form = $this->formFactory->create(TrickFormType::class);
        $form->handleRequest($request);

        if ($this->formHandler->handle($form, $this->security)) {
            return $responder(['slug' => $this->formHandler->getSlug()], 'redirect');
        }

        return $responder(['form' => $form->createView()]);
    }
}
