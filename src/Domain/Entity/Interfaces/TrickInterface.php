<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 6:27 PM.
 */

namespace App\Domain\Entity\Interfaces;

use App\Domain\DTO\CreateTrickDTO;
use App\Domain\Entity\Category;
use App\Domain\Entity\User;
use Doctrine\Common\Collections\Collection;

interface TrickInterface extends EntityInterface
{
    public function __construct(CreateTrickDTO $createTrickDTO);

    public function updateFromDTO(CreateTrickDTO $trickDTO);

    public function getTitle(): ?string;

    public function getDescription(): ?string;

    public function getSlug(): ?string;

    public function getCreatedAt(): ?\DateTimeInterface;

    public function getUpdatedAt(): ?\DateTimeInterface;

    public function getCategory(): ?Category;

    public function getImages(): Collection;

    public function getVideos(): Collection;

    public function getUser(): ?User;

    public function getComments(): Collection;
}
