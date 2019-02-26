<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/24/19
 * Time: 4:11 PM.
 */

namespace App\Form;

use App\Entity\Picture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PictureFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', FileType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Photo de profil'],
                'error_bubbling' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
        ]);
    }
}
