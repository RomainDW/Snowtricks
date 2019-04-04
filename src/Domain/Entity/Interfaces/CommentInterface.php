<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 6:25 PM.
 */

namespace App\Domain\Entity\Interfaces;

use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use DateTimeInterface;

interface CommentInterface extends EntityInterface
{
    public function getContent(): ?string;

    public function getUser(): ?User;

    public function getCreatedAt(): ?DateTimeInterface;

    public function setCreatedAt(DateTimeInterface $created_at);

    public function setUser(?User $user);

    public function setTrick(?Trick $trick);
}
