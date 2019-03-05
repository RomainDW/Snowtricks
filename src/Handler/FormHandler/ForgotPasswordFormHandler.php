<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 3/4/19
 * Time: 6:55 PM.
 */

namespace App\Handler\FormHandler;

use App\Repository\UserRepository;
use Swift_Mailer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ForgotPasswordFormHandler
{
    private $userRepository;
    private $mailer;
    private $flashBag;
    private $url_generator;
    private $templating;

    /**
     * ForgotPasswordFormHandler constructor.
     *
     * @param UserRepository        $userRepository
     * @param Swift_Mailer          $mailer
     * @param FlashBagInterface     $flashBag
     * @param UrlGeneratorInterface $url_generator
     * @param \Twig_Environment     $templating
     */
    public function __construct(UserRepository $userRepository, Swift_Mailer $mailer, FlashBagInterface $flashBag, UrlGeneratorInterface $url_generator, \Twig_Environment $templating)
    {
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
        $this->flashBag = $flashBag;
        $this->url_generator = $url_generator;
        $this->templating = $templating;
    }

    /**
     * @param FormInterface $form
     *
     * @return bool|RedirectResponse
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function handle(FormInterface $form)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->userRepository->findOneBy(['email' => $form->get('email')->getData()]);

            if ($user) {
                $vkey = md5(random_bytes(10));
                $user->setVkey($vkey);

                $message = (new \Swift_Message('Réinitialiser votre mot de passe.'))
                    ->setFrom('noreply@snowtricks.com')
                    ->setTo('romain.ollier34@gmail.com')
                    ->setBody(
                        $this->templating->render(
                            'emails/reset-password.html.twig',
                            ['vkey' => $vkey]
                        ),
                        'text/html'
                    )
                    /*
                     * plaintext version
                    ->addPart(
                        $this->renderView(
                            'emails/reset-password.txt.twig',
                            []
                        ),
                        'text/plain'
                    )
                    */
                ;

                $this->mailer->send($message);

                $this->userRepository->save($user);

                $this->flashBag->add('success', 'Un email vous a été envoyé avec un lien de réinitialisation de mot de passe.');

                return new RedirectResponse($this->url_generator->generate('app_forgot_password'));
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
