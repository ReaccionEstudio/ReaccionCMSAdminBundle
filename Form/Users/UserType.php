<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Users;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
	use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
	use Symfony\Component\Form\Extension\Core\Type\PasswordType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\CollectionType;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Constants\Languages;

	class UserType extends AbstractType
	{
	    public function buildForm(FormBuilderInterface $builder, array $options)
	    {
	    	$passwordAreRequired = ($options['mode'] == "edit") ? false : true;

	        $builder
	            ->add('username', TextType::class, [
	            	'label' => 'users_form.username',
	            	'required' => true
	            ])
	            ->add('nickname', TextType::class, [
	            	'label' => 'users_form.nickname',
	            	'required' => false
	            ])
	            ->add('email', TextType::class, [
	            		'label' => 'users_form.email',
	                    'required' => true
	                ]
	            )
	            ->add('roles', ChoiceType::class, [
	            		'label' => 'users_form.role',
	                    'required' => true,
	                    'choices' => $options['roles'],
	                    'choice_label' => function($choiceValue, $key, $value)
	                    {
	                    	return $value;
	                    },
	                    'multiple' => true
	                ]
	            )
	            ->add('userPassword', RepeatedType::class, [
				    'type' => PasswordType::class,
				    'invalid_message' => 'users_form.password_not_matching',
				    'options' => array('attr' => array('class' => 'password-field')),
				    'required' => $passwordAreRequired,
				    'first_options'  => array('label' => 'users_form.password'),
				    'second_options' => array('label' => 'users_form.repeat_password'),
				    'mapped' => false
				])
	            ->add('enabled', CheckboxType::class, [
	            	'label' => 'users_form.is_enabled',
	            	'required' => false,
	            	'attr' => ['checked' => true]
	            ])
	            ->add('language', ChoiceType::class, [
	            	'label' => 'users_form.language',
	            	'choices' => Languages::LANGUAGES,
	            	'choice_label' => function($choiceValue, $key, $value)
                    {
                    	return Languages::LANGUAGES_ORIGINAL_NAMES[$value];
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
		    	'data_class' => 'App\ReaccionEstudio\ReaccionCMSBundle\Entity\User',
		        'roles' => [],
		        'mode' => 'create'
		    ));
		}
	}