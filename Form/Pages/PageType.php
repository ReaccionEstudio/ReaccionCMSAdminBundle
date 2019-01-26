<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Constants\Languages;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\PageTranslationGroup;

	class PageType extends AbstractType
	{
	    public function buildForm(FormBuilderInterface $builder, array $options)
	    {
	    	$entityManager = $options['entity_manager'];

	        $builder
	            ->add('name', TextType::class, [
	            	'label' => 'page_form.name',
	            	'required' => true
	            ])
	            ->add('translationGroup', EntityType::class, [
	            	'label' => 'users_form.translation_group',
	            	'class' => PageTranslationGroup::class,
	            	'choice_label' => function($choiceValue)
                    {
                    	return $choiceValue->getName();
                    },
	            	'choice_value' => function($choiceValue)
                    {
                    	return ($choiceValue) ? $choiceValue->getId() : '';
                    },
                    'empty_data' => '',
                    'required' => false,
                    'data' => (isset($options['query']['translationGroup'])) 
                    			? $entityManager->getRepository(PageTranslationGroup::class)->findOneBy(['id' => $options['query']['translationGroup']])
                    			: ''
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
			$resolver->setRequired('entity_manager');
		    $resolver->setDefaults(array(
		    	'data_class' => 'ReaccionEstudio\ReaccionCMSBundle\Entity\Page',
		        'templateViews' => [],
		        'query' => []
		    ));
		}
	}