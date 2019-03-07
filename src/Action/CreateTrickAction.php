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
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * CreateTrickAction constructor.
     *
     * @param CreateTrickResponder $responder
     * @param FormFactoryInterface $formFactory
     * @param Security             $security
     */
    public function __construct(CreateTrickResponder $responder, FormFactoryInterface $formFactory, Security $security)
    {
        $this->responder = $responder;
        $this->formFactory = $formFactory;
        $this->security = $security;
    }

    /**
     * @param Request                $request
     * @param CreateTrickFormHandler $formHandler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/trick/add", name="app_create_trick")
     */
    public function __invoke(Request $request, CreateTrickFormHandler $formHandler)
    {
        $form = $this->formFactory->create(TrickFormType::class);
        $form->handleRequest($request);

        if (($response = $formHandler->handle($form, $this->security->getUser())) instanceof RedirectResponse) {
            return $response;
        }

        $responder = $this->responder;

        return $responder($form);
    }
}
