<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/24/19
 * Time: 3:34 PM.
 */

namespace App\Form;

use App\DTO\UpdateUserDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserUpdateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('picture', PictureFormType::class, [
            'label' => 'Votre photo de profil',
            'attr' => ['placeholder' => 'Photo de profil'],
            'required' => false,
            'error_bubbling' => false,
            ])
            ->add('username', TextType::class, [
                'label' => 'Votre Pseudo',
                'attr' => ['placeholder' => 'pseudo'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UpdateUserDTO::class,
            'empty_data' => function (FormInterface $form) {
                return new UpdateUserDTO(
                    $form->get('username')->getData(),
                    $form->get('picture')->getData()
                );
            },
            'validation_groups' => ['update_account'],
        ]);
    }
}
