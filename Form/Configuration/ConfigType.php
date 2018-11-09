<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Configuration;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\TextareaType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

	class ConfigType extends AbstractType
	{
	    public function buildForm(FormBuilderInterface $builder, array $options)
	    {
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
	        ;
	    }
	}