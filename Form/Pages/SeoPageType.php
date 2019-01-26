<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Form\Extension\Core\Type\TextType;

	class SeoPageType extends AbstractType
	{
	    public function buildForm(FormBuilderInterface $builder, array $options)
	    {
	        $builder
	            ->add('slug', TextType::class, [
	            	'label' => 'page_form.slug',
	            	'required' => true
	            ])
	            ->add('seoTitle', TextType::class, [
	            	'label' => 'page_form.seo_title',
	            	'required' => false
	            ])
	            ->add('seoDescription', TextType::class, [
	            	'label' => 'page_form.seo_description',
	            	'required' => false
	            ])
	            ->add('seoKeywords', TextType::class, [
	            	'label' => 'page_form.seo_description',
	            	'required' => false
	            ])
	        ;
	    }
	}