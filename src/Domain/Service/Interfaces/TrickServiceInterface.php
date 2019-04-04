<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 6:41 PM.
 */

namespace App\Domain\Service\Interfaces;

use App\Domain\DTO\CreateTrickDTO;
use App\Domain\Entity\Interfaces\TrickInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface TrickServiceInterface
{
    public function __construct(
        EventDispatcherInterface $dispatcher,
        ValidatorInterface $validator,
        ManagerRegistry $doctrine,
        FlashBagInterface $flashBag
    );

    public function InitTrick(CreateTrickDTO $createTrickDTO, UserInterface $user);

    public function UpdateTrick(TrickInterface $trick, CreateTrickDTO $trickDTO);

    public function save(TrickInterface $trick, string $type = 'add');

    public function deleteTrick(TrickInterface $trick);

    public function getSlug();
}
