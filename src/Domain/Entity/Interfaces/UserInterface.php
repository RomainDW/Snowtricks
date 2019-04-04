<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 6:55 PM.
 */

namespace App\Domain\Entity\Interfaces;

use App\Domain\DTO\UpdateUserDTO;
use App\Domain\DTO\UserRegistrationDTO;
use App\Domain\Entity\Picture;
use Doctrine\Common\Collections\Collection;

interface UserInterface extends \Symfony\Component\Security\Core\User\UserInterface
{
    public function getId();

    public function getEmail(): ?string;

    public function getVkey(): ?string;

    public function getUsername(): string;

    public function getRoles(): array;

    public function getPassword(): string;

    public function updateRole(array $roles);

    public function setVkey(string $vkey);

    public function hasRole(string $role);

    public function exist(string $email);

    public function newPassword(string $password);

    public function createFromRegistration(UserRegistrationDTO $registrationDTO): self;

    public function updateFromDTO(UpdateUserDTO $userDTO);

    public function getTricks(): Collection;

    public function getComments(): Collection;

    public function getPicture(): ?Picture;
}
