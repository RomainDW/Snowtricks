<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 1:17 PM.
 */

namespace App\Tests\Domain\Entity;

use App\Domain\Entity\Comment;
use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use DateTime;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    public function testImplementation()
    {
        $content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

        $trick = $this->createMock(Trick::class);
        $user = $this->createMock(User::class);

        $comment = new Comment();
        $comment->setContent($content);
        $comment->setTrick($trick);
        $comment->setUser($user);
        $comment->setCreatedAt(new DateTime());

        static::assertSame($content, $comment->getContent());
        static::assertSame($user, $comment->getUser());
        static::assertSame($trick, $comment->getTrick());
        static::assertInstanceOf(DateTimeInterface::class, $comment->getCreatedAt());

    }
}
