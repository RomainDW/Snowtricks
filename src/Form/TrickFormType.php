<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/2/19
 * Time: 12:18 PM.
 */

namespace App\Form;

use App\Domain\DTO\CreateTrickDTO;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => 'App\Domain\Entity\Category',
                'choice_label' => 'name',
            ])
            ->add('images', CollectionType::class, [
                'label' => 'Images',
                'entry_type' => ImageFormType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('videos', CollectionType::class, [
                'label' => 'Vidéos',
                'entry_type' => VideoFormType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CreateTrickDTO::class,
            'empty_data' => function (FormInterface $form) {
                return new CreateTrickDTO(
                    $form->get('title')->getData(),
                    $form->get('description')->getData(),
                    $form->get('category')->getData(),
                    $form->get('images')->getData(),
                    $form->get('videos')->getData()
                );
            },
            'validation_groups' => ['Default', 'add_trick'],
        ]);
    }
}
