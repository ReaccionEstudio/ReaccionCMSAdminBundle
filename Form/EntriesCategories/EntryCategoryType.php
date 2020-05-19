<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Form\EntriesCategories;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\EntryCategory;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Constants\Languages;

	class EntryCategoryType extends AbstractType
	{
	    public function buildForm(FormBuilderInterface $builder, array $options)
	    {
	        $builder
	            ->add('name', TextType::class, [
	            	'label' => 'entries_categories_form.name',
	            	'required' => true
	            ])
	            ->add('language', ChoiceType::class, [
	            	'label' => 'entries_categories_form.language',
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
	            ->add('enabled', CheckboxType::class, [
	            	'label' => 'entries_categories_form.isEnabled',
	            	'attr' => ($options['mode'] == "create") ? ['checked' => 'checked'] : [],
	            	'required' => false
	            ])
	        ;
	    }

	    public function configureOptions(OptionsResolver $resolver)
		{
		    $resolver->setDefaults(array(
		    	'data_class' => 'ReaccionEstudio\ReaccionCMSBundle\Entity\EntryCategory',
		        'mode' => 'create'
		    ));
		}
	}