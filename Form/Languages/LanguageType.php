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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class LanguageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $language = ($options['language']) ?? null;
        $enabled = true;

        if($language != null)
        {
            $enabled = $language->isEnabled();
        }

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
                'attr' => ['checked' => $enabled ]
            ])
            ->add('position', NumberType::class, [
                'label' => 'languages_crud.position',
                'required' => false
            ])
            ->add('main', CheckboxType::class, [
                'label' => 'languages_crud.is_default',
                'required' => false
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event)
        {
            $form = $event->getForm();

            if( ! $form['main']->getData())
            {
                $form['main']->setData(null);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ReaccionEstudio\ReaccionCMSBundle\Entity\Language',
            'mode' => 'create',
            'language' => null
        ));
    }
}