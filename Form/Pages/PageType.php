<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Constants\Languages;

	class PageType extends AbstractType
	{
	    public function buildForm(FormBuilderInterface $builder, array $options)
	    {
	        $builder
	            ->add('name', TextType::class, [
	            	'label' => 'page_form.name',
	            	'required' => true
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
				->add('templateView', ChoiceType::class, [
	            	'label' => 'page_form.template_view',
	            	'required' => true,
	            	'choices' => $options['templateViews'],
	            	'choice_label' => function($choiceValue, $key, $value)
                    {
                    	return $value;
                    },
	            	'choice_value' => function($value)
                    {
                    	return $value;
                    },
	            ])
	            ->add('isEnabled', CheckboxType::class, [
	            	'label' => 'page_form.is_enabled',
	            	'required' => false
	            ])
	            ->add('mainPage', CheckboxType::class, [
	            	'label' => 'page_form.is_main_page',
	            	'required' => false
	            ])
	        ;
	    }

	    public function configureOptions(OptionsResolver $resolver)
		{
		    $resolver->setDefaults(array(
		    	'data_class' => 'App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page',
		        'templateViews' => []
		    ));
		}
	}