<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 10:48 AM.
 */

namespace App\Tests\Form;

use App\Form\TrickFormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class TrickFormTypeTest extends TypeTestCase
{
    private $systemUnderTest;

    protected function setUp()
    {
        parent::setUp();
        $this->systemUnderTest = new TrickFormType();
    }

    /**
     * Tests that form is correctly build according to specs.
     */
    public function testBuildForm(): void
    {
        $formBuilderMock = $this->createMock(FormBuilderInterface::class);

        // Tests number of calls to add method
        $formBuilderMock->expects($this->exactly(5))->method('add')->willReturnSelf()->withConsecutive(
            [$this->equalTo('title'), $this->equalTo(TextType::class)],
            [$this->equalTo('description'), $this->equalTo(TextareaType::class)],
            [$this->equalTo('category'), $this->equalTo(EntityType::class)],
            [$this->equalTo('images'), $this->equalTo(CollectionType::class)],
            [$this->equalTo('videos'), $this->equalTo(CollectionType::class)]
        );


        // Passing the mock as a parameter and an empty array as options as I don't test its use
        $this->systemUnderTest->buildForm($formBuilderMock, []);
    }

    // see : https://stackoverflow.com/questions/49101275/symfony-form-too-few-arguments-to-function-doctrinetype-construct
    public function testSubmitValidData()
    {
        $formData = [
            'title' => 'test',
            'description' => 'test',
            'category' => 'test',
            'image' => 'test',
            'video' => 'test',
        ];

        $objectToCompare = new TestObject();

        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(TrickFormType::class);

        $object = new TestObject();
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
