<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Form\Configuration;

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
	    	$valueInputClass = '';

	    	if( ! empty($options['config']) && in_array($options['config']->getType(), ['image','serialized']) )
	    	{
	    		$valueInputClass = 'd-none';
	    	}

	    	// config entity fields
	        $builder
	            ->add('name', TextType::class, [
	            	'label' => 'config_form.name',
	            	'required' => true
	            ])
	            ->add('value', TextareaType::class, [
	            	'label' => 'config_form.value',
	            	'required' => false,
	            	'attr' => array('rows' => 8, 'class' => $valueInputClass),
	            	'label_attr' => array('class' => $valueInputClass)
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
	        	else if($type == "serialized")
	        	{
	        		$value = $options['config']->getValue();
	        		$serializedData = unserialize($value);

	        		foreach($serializedData as $key => $value)
	        		{
	        			$builder->add($key, TextType::class, [
				            		  'label' => $key,
				            		  'required' => false,
				            		  'mapped' => false,
				            		  'data' => $value
				            	]);
	        		}
	        	}
	        }
	    }

	    public function configureOptions(OptionsResolver $resolver)
		{
		    $resolver->setDefaults(array(
		    	'data_class' => 'ReaccionEstudio\ReaccionCMSBundle\Entity\Configuration',
		        'config' => []
		    ));
		}
	}