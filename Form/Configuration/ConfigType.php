<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Configuration;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type\HiddenType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\TextareaType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
	use Symfony\Component\Form\Extension\Core\Type\FileType;

	class ConfigType extends AbstractType
	{
	    public function buildForm(FormBuilderInterface $builder, array $options)
	    {
	    	// config entity fields
	        $builder
	            ->add('name', TextType::class, [
	            	'label' => 'config_form.name',
	            	'required' => true
	            ])
	            ->add('value', TextareaType::class, [
	            	'label' => 'config_form.value',
	            	'required' => false,
	            	'attr' => array('rows' => 8)
	            ])
	            ->add('type', HiddenType::class, [
	            	'required' => false
	            ])
	        ;

	        if( ! empty($options['config']) )
	        {
	        	// get config type
	        	$type = $options['config']->getType();

	        	if($type == "image")
	        	{
	        		$builder->add('image', FileType::class, [
	        			'label' => 'config_form.image',
	        			'required' => false,
	        			'mapped' => false,
	        			'attr' => ['class' => 'image-picker'],
	        			'label_attr' => ['class' => 'image-picker-label']
	        		]);
	        	}
	        }
	    }

	    public function configureOptions(OptionsResolver $resolver)
		{
		    $resolver->setDefaults(array(
		    	'data_class' => 'App\ReaccionEstudio\ReaccionCMSBundle\Entity\Configuration',
		        'config' => []
		    ));
		}
	}