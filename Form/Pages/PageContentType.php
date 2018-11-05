<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\IntegerType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\TextareaType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

	class PageContentType extends AbstractType
	{
		CONST PageContentTypesList = array(

			'Text / HTML' => 'text_html',
			'Image' => 'img'

		);

	    public function buildForm(FormBuilderInterface $builder, array $options)
	    {
	        $builder
	            ->add('name', TextType::class, [
	            	'label' => 'Name / Alias',
	            	'required' => false
	            ])
	            ->add('type', ChoiceType::class, [
	            	'label' => 'Type',
	            	'choices' => self::PageContentTypesList,
	            	'placeholder' => '',
	            	'required' => true
	            ])
	            ->add('value', TextareaType::class, [
	            	'label' => 'Value',
	            	'required' => true,
	            	'attr' => array('rows' => 8)
	            ])
	            ->add('isEnabled', CheckboxType::class, [
	            	'label' => 'Is enabled?',
	            	'attr' => array('checked' => 'checked'),
	            	'required' => false
	            ])
	        ;
	    }
	}