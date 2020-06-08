<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Form\Entries;

use ReaccionEstudio\ReaccionCMSBundle\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use ReaccionEstudio\ReaccionCMSAdminBundle\Constants\Languages;

/**
 * Class EntryType
 * @package ReaccionEstudio\ReaccionCMSAdminBundle\Form\Entries
 */
class EntryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $mode = $options['mode'] ?? '';

        $builder
            ->add('name', TextType::class, [
                'label' => 'entries_form.name',
                'required' => true,
                'attr' => ['placeholder' => 'entries_form.name']
            ])
            ->add('content', TextareaType::class, [
                'label' => 'entries_form.content',
                'attr' => ['style' => 'display:none'],
                'required' => true
            ])
            ->add('categories', EntityType::class, [
                'class' => 'ReaccionEstudio\ReaccionCMSBundle\Entity\EntryCategory',
                'choice_attr' => function ($choiceValue, $key, $value) {
                    return ['data-language' => $choiceValue->getLanguage() ?? 'null'];
                },
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('slug', TextType::class, [
                'label' => 'entries_form.slug',
                'required' => ($mode === 'edit') ? true : false
            ])
            ->add('tags', TextType::class, [
                'label' => 'entries_form.tags',
                'required' => false
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'entries_form.isEnabled',
                'attr' => ($options['mode'] == "create") ? ['checked' => 'checked'] : [],
                'required' => false
            ])
            ->add('language', ChoiceType::class, [
                'label' => 'users_form.language',
                'choices' => Languages::LANGUAGES,
                'choice_label' => function($choiceValue, $key, $value)
                {
                    return 'languages.' . $value;
                },
                'choice_value' => function($value)
                {
                    return $value;
                },
                'required' => false,
                'attr' => ['class' => 'selectize']
            ])
            ->add('defaultImageFile', FileType::class, [
                'label' => 'entries_form.defaultImage',
                'required' => false,
                'mapped' => false
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ReaccionEstudio\ReaccionCMSBundle\Entity\Entry',
            'mode' => 'create'
        ));
    }
}
