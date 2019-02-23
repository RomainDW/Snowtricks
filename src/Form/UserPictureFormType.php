<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/18/19
 * Time: 8:09 PM.
 */

namespace App\Form;

use App\DTO\PictureDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPictureFormType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Photo de profil'],
                'error_bubbling' => true,
            ])
            ->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PictureDTO::class,
            'empty_data' => null,
        ]);
    }

    /**
     * Maps properties of some data to a list of forms.
     *
     * @param $pictureDTO
     * @param FormInterface[]|\Traversable $forms A list of {@link FormInterface} instances
     */
    public function mapDataToForms($pictureDTO, $forms)
    {
        $forms = iterator_to_array($forms);
        $forms['file']->setData($pictureDTO ? $pictureDTO->file : '');
    }

    /**
     * Maps the data of a list of forms into the properties of some data.
     *
     * @param FormInterface[]|\Traversable $forms A list of {@link FormInterface} instances
     * @param $pictureDTO
     * @throws \Exception
     */
    public function mapFormsToData($forms, &$pictureDTO)
    {
        $forms = iterator_to_array($forms);
        $pictureDTO = new PictureDTO(
            $forms['file']->getData()
        );
    }
}
