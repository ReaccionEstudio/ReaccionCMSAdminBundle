<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Form\Extension\Core\Type\TextType;

	class SeoPageType extends AbstractType
	{
	    public function buildForm(FormBuilderInterface $builder, array $options)
	    {
	        $builder
	            ->add('slug', TextType::class, [
	            	'label' => 'Slug / Page url',
	            	'required' => true
	            ])
	            ->add('seoTitle', TextType::class, [
	            	'label' => 'Title',
	            	'required' => false
	            ])
	            ->add('seoDescription', TextType::class, [
	            	'label' => 'Description',
	            	'required' => false
	            ])
	            ->add('seoKeywords', TextType::class, [
	            	'label' => 'Keywords',
	            	'required' => false
	            ])
	        ;
	    }
	}