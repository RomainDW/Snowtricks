<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 11:34 AM.
 */

namespace App\Tests\Form;

use App\Domain\DTO\UserRegistrationDTO;
use App\Form\PictureFormType;
use App\Form\RegistrationFormType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class RegistrationFormTypeTest extends TypeTestCase
{
    private $systemUnderTest;

    protected function setUp()
    {
        parent::setUp();
        $this->systemUnderTest = new RegistrationFormType();
    }

    /**
     * Tests that form is correctly build according to specs.
     */
    public function testBuildForm(): void
    {
        $formBuilderMock = $this->createMock(FormBuilderInterface::class);

        // Tests number of calls to add method
        $formBuilderMock->expects($this->exactly(4))->method('add')->willReturnSelf()->withConsecutive(
            [$this->equalTo('picture'), $this->equalTo(PictureFormType::class)],
            [$this->equalTo('username'), $this->equalTo(TextType::class)],
            [$this->equalTo('email'), $this->equalTo(EmailType::class)],
            [$this->equalTo('plainPassword'), $this->equalTo(RepeatedType::class)]
        );

        // Passing the mock as a parameter and an empty array as options as I don't test its use
        $this->systemUnderTest->buildForm($formBuilderMock, []);
    }
}
