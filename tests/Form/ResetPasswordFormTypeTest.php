<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 11:31 AM.
 */

namespace App\Tests\Form;

use App\Form\ResetPasswordFormType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class ResetPasswordFormTypeTest extends TypeTestCase
{
    private $systemUnderTest;

    protected function setUp()
    {
        parent::setUp();
        $this->systemUnderTest = new ResetPasswordFormType();
    }

    /**
     * Tests that form is correctly build according to specs.
     */
    public function testBuildForm(): void
    {
        $formBuilderMock = $this->createMock(FormBuilderInterface::class);

        // Tests number of calls to add method
        $formBuilderMock->expects($this->exactly(2))->method('add')->willReturnSelf()->withConsecutive(
            [$this->equalTo('email'), $this->equalTo(EmailType::class)],
            [$this->equalTo('plainPassword'), $this->equalTo(RepeatedType::class)]
        );

        // Passing the mock as a parameter and an empty array as options as I don't test its use
        $this->systemUnderTest->buildForm($formBuilderMock, []);
    }
}
