<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Users;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
	use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
	use Symfony\Component\Form\Extension\Core\Type\PasswordType;

	class UserType extends AbstractType
	{
	    public function buildForm(FormBuilderInterface $builder, array $options)
	    {
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
	            ->add('password', RepeatedType::class, [
				    'type' => PasswordType::class,
				    'invalid_message' => 'users_form.password_not_matching',
				    'options' => array('attr' => array('class' => 'password-field')),
				    'required' => true,
				    'first_options'  => array('label' => 'users_form.password'),
				    'second_options' => array('label' => 'users_form.repeat_password')
				])
	            ->add('isEnabled', CheckboxType::class, [
	            	'label' => 'users_form.is_enabled',
	            	'required' => false,
	            	'attr' => ['checked' => true]
	            ])
	            ->add('language', TextType::class, [
	            	'label' => 'users_form.language',
	            	'required' => false
	            ])
	        ;
	    }
	}