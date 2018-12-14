<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Form\Extension\Core\Type\IntegerType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\TextareaType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\HiddenType;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Constants\PageContentTypes;

	class PageContentType extends AbstractType
	{
	    public function buildForm(FormBuilderInterface $builder, array $options)
	    {
	        $builder
	            ->add('name', TextType::class, [
	            	'label' => 'page_content_form.name',
	            	'required' => false
	            ])
	            ->add('type', ChoiceType::class, [
	            	'label' => 'page_content_form.type',
	            	'choices' => PageContentTypes::PageContentTypesList,
	            	'placeholder' => '',
	            	'required' => true
	            ])
	            ->add('value', TextareaType::class, [
	            	'label' => 'page_content_form.value',
	            	'required' => true,
	            	'attr' => array('rows' => 8)
	            ])
	            ->add('isEnabled', CheckboxType::class, [
	            	'label' => 'page_content_form.is_enabled',
	            	'attr' => array('checked' => 'checked'),
	            	'required' => false
	            ])
	        ;
	    }
	}