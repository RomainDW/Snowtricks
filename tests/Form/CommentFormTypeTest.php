<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 11:42 AM.
 */

namespace App\Tests\Form;

use App\Domain\Entity\Comment;
use App\Form\CommentFormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class CommentFormTypeTest extends TypeTestCase
{
    private $systemUnderTest;

    protected function setUp()
    {
        parent::setUp();
        $this->systemUnderTest = new CommentFormType();
    }

    /**
     * Tests that form is correctly build according to specs.
     */
    public function testBuildForm(): void
    {
        $formBuilderMock = $this->createMock(FormBuilderInterface::class);

        // Tests number of calls to add method
        $formBuilderMock->expects($this->exactly(1))->method('add')->willReturnSelf()->withConsecutive(
            [$this->equalTo('content'), $this->equalTo(TextareaType::class)]
        );

        // Passing the mock as a parameter and an empty array as options as I don't test its use
        $this->systemUnderTest->buildForm($formBuilderMock, []);
    }

    public function testSubmitValidData()
    {
        $formData = [
            'content' => 'test',
        ];

        $objectToCompare = new Comment();
        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(CommentFormType::class, $objectToCompare);

        $object = new Comment();
        // ...populate $object properties with the data stored in $formData

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        // check that $objectToCompare was modified as expected when the form was submitted
        $this->assertNotEquals($object, $objectToCompare);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
