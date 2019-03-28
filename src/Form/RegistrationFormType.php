<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/1/19
 * Time: 12:24 PM.
 */

namespace App\Form;

use App\DTO\UserRegistrationDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $option
     */
    public function buildForm(FormBuilderInterface $builder, array $option): void
    {
        $builder
            ->add('picture', PictureFormType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Photo de profil'],
                'required' => false,
                'help' => 'photo de profil pas obligatoire',
                'error_bubbling' => true,
            ])
            ->add('username', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'pseudo', 'autofocus' => 'autofocus'],
                'error_bubbling' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'e-mail'],
                'help' => 'un email de confirmation vous sera envoyé.',
                'error_bubbling' => true,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs de mot de passe doivent correspondre.',
                'required' => true,
                'first_options' => ['label' => false, 'attr' => ['placeholder' => 'mot de passe']],
                'second_options' => ['label' => false, 'attr' => ['placeholder' => 'confirmer mot de passe']],
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'error_bubbling' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserRegistrationDTO::class,
            'empty_data' => function (FormInterface $form) {
                return new UserRegistrationDTO(
                    $form->get('username')->getData(),
                    $form->get('email')->getData(),
                    $form->get('plainPassword')->getData(),
                    md5(random_bytes(10)),
                    $form->get('picture')->getData()
                );
            },
            'validation_groups' => ['registration'],
        ]);
    }
}
