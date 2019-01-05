<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Entries;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\TextareaType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\EntryCategory;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Constants\Languages;

	class EntryType extends AbstractType
	{
	    public function buildForm(FormBuilderInterface $builder, array $options)
	    {
	        $builder
	            ->add('name', TextType::class, [
	            	'label' => 'entries_form.name',
	            	'required' => true,
	            	'attr' => ['placeholder' => 'entries_form.name']
	            ])
	            ->add('content', TextareaType::class, [
	            	'label' => 'entries_form.content',
	            	'attr' => ['style' =>'display:none'],
	            	'required' => true
	            ])
	            ->add('categories', EntityType::class, [
	            	'class' => 'App\ReaccionEstudio\ReaccionCMSBundle\Entity\EntryCategory',
	            	'choice_attr' => function($choiceValue, $key, $value) 
	            	{
	            		return ['data-language' => $choiceValue->getLanguage() ];
	            	},
	            	'choice_label' => 'name',
		            'expanded'  => true,
                	'multiple'  => true,
		        ])
	            ->add('slug', TextType::class, [
	            	'label' => 'entries_form.slug',
	            	'required' => true
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
	        ;
	    }

	    public function configureOptions(OptionsResolver $resolver)
		{
		    $resolver->setDefaults(array(
		    	'data_class' => 'App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry',
		        'mode' => 'create'
		    ));
		}
	}