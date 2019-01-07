<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Configuration;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\PasswordType;

	class MailerConfigType extends AbstractType
	{
	    public function buildForm(FormBuilderInterface $builder, array $options)
	    {
	    	// config entity fields
	        $builder
	            ->add('host', TextType::class, [
	            	'label' => 'preferences_mailer.host',
	            	'required' => true,
	            	'data' => ($options['config']['host']) ?? ''
	            ])
	            ->add('port', TextType::class, [
	            	'label' => 'preferences_mailer.port',
	            	'data' => ($options['config']['port']) ?? '',
	            	'required' => true
	            ])
	            ->add('username', TextType::class, [
	            	'label' => 'preferences_mailer.username',
	            	'data' => ($options['config']['username']) ?? '',
	            	'required' => true
	            ])
	            ->add('password', PasswordType::class, [
	            	'label' => 'preferences_mailer.password',
	            	'required' => true
	            ])
	            ->add('authentication', ChoiceType::class, [
	            	'label' => 'preferences_mailer.authentication',
	            	'data' => ($options['config']['authentication']) ?? '',
	            	'choices' => [
	            		'SSL' => 'ssl',
	            		'TLS' => 'tls'
	            	],
	            	'placeholder' => 'preferences_mailer.none',
	            	'required' => false
	            ])
	        ;
	    }

	    public function configureOptions(OptionsResolver $resolver)
		{
		    $resolver->setDefaults(array(
		        'config' => []
		    ));
		}
 	}