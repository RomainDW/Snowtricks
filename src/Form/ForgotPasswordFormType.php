<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/16/19
 * Time: 10:00 AM.
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;

class ForgotPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class, [
            'label' => false,
            'required' => true,
            'attr' => ['placeholder' => 'Votre adresse email', 'class' => 'fadeIn second form-input', 'autofocus' => 'autofocus'],
            'help' => 'un email vous sera envoyé pour réinitialiser votre mot de passe',
        ]);
    }
}
