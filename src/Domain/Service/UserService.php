<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/17/19
 * Time: 11:29 AM.
 */

namespace App\Domain\Service;

use App\Domain\Entity\Interfaces\UserInterface;
use App\Domain\Entity\User;
use App\Domain\Exception\ValidationException;
use App\Domain\Notifier\MailNotifier;
use App\Domain\Service\Interfaces\UserServiceInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig_Error_Loader;
use Twig_Error_Runtime;

class UserService implements UserServiceInterface
{
    private $validator;
    private $doctrine;
    private $flashBag;
    private $notifier;
    private $userId;

    /**
     * UserService constructor.
     *
     * @param ValidatorInterface $validator
     * @param ManagerRegistry    $doctrine
     * @param FlashBagInterface  $flashBag
     * @param MailNotifier       $notifier
     */
    public function __construct(ValidatorInterface $validator, ManagerRegistry $doctrine, FlashBagInterface $flashBag, MailNotifier $notifier)
    {
        $this->validator = $validator;
        $this->doctrine = $doctrine;
        $this->flashBag = $flashBag;
        $this->notifier = $notifier;
    }

    /**
     * @param UserInterface $user
     *
     * @throws ValidationException
     */
    public function save(UserInterface $user)
    {
        if (count($errors = $this->validator->validate($user))) {
            throw new ValidationException($errors);
        }

        $manager = $this->doctrine->getManager();
        $manager->persist($user);
        $manager->flush();
    }

    /**
     * @param UserInterface $user
     *
     * @throws ValidationException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function register(UserInterface $user)
    {
        $this->save($user);

        $this->userId = $user->getId();

        $this->notifier->notifyRegistration($user, $user->getEmail());
    }

    /**
     * @param UserInterface $user
     *
     * @throws ValidationException
     */
    public function update(UserInterface $user)
    {
        $this->save($user);
        $this->flashBag->add('success', 'Votre compte a bien été modifié !');
    }

    /**
     * @param UserInterface $user
     *
     * @return bool
     */
    public function verification(UserInterface $user): bool
    {
        $hasAccess = $user->hasRole('ROLE_USER_NOT_VERIFIED');

        if (!$hasAccess) {
            return false;
        } else {
            $user->updateRole(['ROLE_USER']);
            $user->eraseCredentials();
            $manager = $this->doctrine->getManager();
            $manager->persist($user);
            $manager->flush();

            return true;
        }
    }

    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $email
     *
     * @throws Exception
     */
    public function forgotPassword(string $email)
    {
        $manager = $this->doctrine->getManager();
        $user = $manager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (null !== $user) {
            $vkey = md5(random_bytes(10));
            $user->setVkey($vkey);
        }

        if ($user instanceof User) {
            $this->save($user);
            $this->notifier->notifyResetPassword($user, $email);
        }

        $this->flashBag->add('success', 'Un email vous a été envoyé avec un lien de réinitialisation de mot de passe.');
    }
}
