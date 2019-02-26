<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/24/19
 * Time: 2:54 PM.
 */

namespace App\Controller;

use App\DTO\UpdateUserDTO;
use App\Event\UserPictureRemoveEvent;
use App\Event\UserPictureUploadEvent;
use App\Form\UserUpdateFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/my-account", name="app_account")
     *
     * @param Request                  $request
     * @param EntityManagerInterface   $em
     * @param EventDispatcherInterface $dispatcher
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        $user = $this->getUser();
        $userDTO = UpdateUserDTO::createFromUser($user);

        $form = $this->createForm(UserUpdateFormType::class, $userDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updatedUserDTO = $form->getData();

            if (null !== $updatedUserDTO->picture && null !== $updatedUserDTO->picture->getFile()) {
                if (null !== $user->getPicture()) {
                    $removeEvent = new UserPictureRemoveEvent($user->getPicture());
                    $dispatcher->dispatch(UserPictureRemoveEvent::NAME, $removeEvent);
                } else {
                    $updatedUserDTO->picture->setUser($user);
                }
                $uploadEvent = new UserPictureUploadEvent($updatedUserDTO->picture);
                $dispatcher->dispatch(UserPictureUploadEvent::NAME, $uploadEvent);
                $updatedUserDTO->picture->setFile(null);
                $updatedUserDTO->picture->setAlt('Photo de profil de '.$user->getUsername());
            }

            $user->updateFromDTO($updatedUserDTO);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre compte a bien été modifié !');

            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/my-account.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
