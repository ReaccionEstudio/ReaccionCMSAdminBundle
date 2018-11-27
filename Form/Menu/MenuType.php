<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Menu;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

	use Doctrine\ORM\EntityRepository;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;

	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;

	class MenuType extends AbstractType
	{
	    public function buildForm(FormBuilderInterface $builder, array $options)
	    {
	        $builder
	            ->add('name', TextType::class, [
	            	'label' => 'menu_form.name',
	            	'required' => true
	            ])
	            ->add('type', ChoiceType::class, [
	            	'label' => 'menu_form.type',
	            	'required' => true,
	            	'choices' => [
	            		'menu_form.url_type' => 'url',
	            		'menu_form.page_type' => 'page'
	            	],
	            	'placeholder' => '',
	            	'required' => true
	            ])
	            ->add('urlValue', TextType::class, [
	            	'label' => 'menu_form.url_value',
	            	'mapped' => false,
	            	'required' => false,
	            	'data' => '#'
	            ])
	            ->add('pageValue', EntityType::class, [
	            	'label' => 'menu_form.page_value',
				    'class' => Page::class,
				    'choice_label' => 'name',
				    'query_builder' => function (EntityRepository $er) 
				    {
				        return $er->createQueryBuilder('p')
				            ->orderBy('p.name', 'ASC');
				    },
				    'mapped' => false,
				    'required' => false
				])
				->add('target', ChoiceType::class, [
	            	'label' => 'menu_form.target',
	            	'required' => true,
	            	'choices' => [
	            		'_blank' => '_blank',
	            		'_self' => '_self',
	            		'_parent' => '_parent',
	            		'_top' => '_top'
	            	],
	            	'placeholder' => '',
	            	'required' => true
	            ])
	            ->add('enabled', CheckboxType::class, [
	            	'label' => 'menu_form.is_enabled',
	            	'attr' => array('checked' => 'checked'),
	            	'required' => true
	            ])
	        ;
	    }
	}