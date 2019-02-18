<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/15/19
 * Time: 10:00 PM.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPasswordFormType;
use App\Form\ResetPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ForgotPasswordController extends AbstractController
{
    /**
     * @Route("/forgot-password", name="app_forgot_password")
     *
     * @param Request                $request
     * @param EntityManagerInterface $em
     * @param \Swift_Mailer          $mailer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function index(Request $request, EntityManagerInterface $em, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(ForgotPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepo = $em->getRepository(User::class);

            $user = $userRepo->findOneBy(['email' => $form->get('email')->getData()]);

            if ($user) {
                $vkey = md5(random_bytes(10));
                $user->setVkey($vkey);

                $message = (new \Swift_Message('Réinitialiser votre mot de passe.'))
                    ->setFrom('noreply@snowtricks.com')
                    ->setTo('romain.ollier34@gmail.com')
                    ->setBody(
                        $this->renderView(
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

                $mailer->send($message);

                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'Un email vous a été envoyé avec un lien de réinitialisation de mot de passe.');
                return $this->redirectToRoute('app_forgot_password');
            }
        }

        return $this->render('security/forgot-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reset-password/{vkey}", name="app_reset_password")
     *
     * @param User                         $user
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface       $em
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resetPassword(User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $user->exist($form->get('email')->getData())) {
            $password = $passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData());
            $user->newPassword($password);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre mot de passe a été réinitialisé, vous pouvez maintenant vous connecter');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
