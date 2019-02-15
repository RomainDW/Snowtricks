<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/2/19
 * Time: 2:15 PM.
 */

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('embed', TextType::class, [
            'label' => false,
            'attr' => ['placeholder' => 'url de la vidéo'],
            'help' => 'Youtube, Dailymotion ou Vimeo.',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
