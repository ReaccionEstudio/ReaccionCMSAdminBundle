<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
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
	}