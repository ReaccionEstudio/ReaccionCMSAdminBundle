<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

	class PageType extends AbstractType
	{
	    public function buildForm(FormBuilderInterface $builder, array $options)
	    {
	        $builder
	            ->add('name', TextType::class, [
	            	'label' => 'page_form.name',
	            	'required' => true
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