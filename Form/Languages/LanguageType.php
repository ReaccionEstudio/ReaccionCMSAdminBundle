<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Form\Languages;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use ReaccionEstudio\ReaccionCMSAdminBundle\Constants\Languages;

class LanguageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'languages_crud.name',
                'required' => true
            ])
            ->add('flag', ChoiceType::class, [
                'label' => 'languages_crud.flag',
                'choices' => Languages::LANGUAGE_ICONS,
                'choice_label' => function($value, $key, $choiceValue)
                {
                    return Languages::LANGUAGES_ORIGINAL_NAMES[$value];
                },
                'choice_value' => function($value)
                {
                    return $value;
                },
                'required' => false
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'languages_crud.is_enabled',
                'required' => false,
                'attr' => ['checked' => true]
            ])
            ->add('position', NumberType::class, [
                'label' => 'languages_crud.position',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ReaccionEstudio\ReaccionCMSBundle\Entity\Language',
            'mode' => 'create'
        ));
    }
}