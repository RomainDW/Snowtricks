<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 2:06 PM.
 */

namespace App\Tests\Domain\Entity;

use App\Domain\DTO\UserRegistrationDTO;
use App\Domain\Entity\Picture;
use App\Domain\Entity\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

class UserTest extends TestCase
{
    public function testImplementation()
    {
        $email = 'test@email.com';
        $roles = ['USER_TEST'];
        $password = 'test';
        $vkey = md5(random_bytes(10));
        $username = 'test';
        $picture = $this->createMock(Picture::class);

        $userRegistrationDTO = new UserRegistrationDTO($username, $email, $password, $vkey, $picture, $roles);

        $user = new User();
        $user->createFromRegistration($userRegistrationDTO);

        static::assertInstanceOf(UuidInterface::class, $user->getId());
        static::assertSame($email, $user->getEmail());
        static::assertSame($roles, $user->getRoles());
        static::assertSame($password, $user->getPassword());
        static::assertSame($vkey, $user->getVkey());
        static::assertSame($username, $user->getUsername());
        static::assertSame($picture, $user->getPicture());
    }
}
