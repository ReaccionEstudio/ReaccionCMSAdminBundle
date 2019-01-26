<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Form\EmailTemplates;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\TextareaType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\EntryCategory;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Constants\Languages;

	class EmailTemplateType extends AbstractType
	{
	    public function buildForm(FormBuilderInterface $builder, array $options)
	    {
	        $builder
	            ->add('name', TextType::class, [
	            	'label' => 'email_templates.name',
	            	'required' => true
	            ])
	            ->add('type', TextType::class, [
	            	'label' => 'email_templates.type',
	            	'required' => false
	            ])
	            ->add('slug', TextType::class, [
	            	'label' => 'email_templates.slug',
	            	'required' => true
	            ])
	            ->add('templateFile', TextType::class, [
	            	'label' => 'email_templates.template_file',
	            	'required' => false
	            ])
	            ->add('fromname', TextType::class, [
	            	'label' => 'email_templates.fromname',
	            	'required' => false
	            ])
	            ->add('fromemail', TextType::class, [
	            	'label' => 'email_templates.fromemail',
	            	'required' => false
	            ])
	            ->add('subject', TextType::class, [
	            	'label' => 'email_templates.subject',
	            	'required' => false
	            ])
	            ->add('message', TextareaType::class, [
	            	'label' => 'email_templates.message',
	            	'attr' => [ 'rows' => 12 ],
	            	'required' => true
	            ])
	            ->add('language', ChoiceType::class, [
	            	'label' => 'email_templates.language',
	            	'choices' => Languages::LANGUAGES,
	            	'choice_label' => function($choiceValue, $key, $value)
                    {
                    	return 'languages.' . $value;
                    },
	            	'choice_value' => function($value)
                    {
                    	return $value;
                    },
	            	'required' => true,
	            	'attr' => ['class' => 'selectize']
	            ])
	            ->add('plainText', CheckboxType::class, [
	            	'label' => 'email_templates.plain_text',
	            	'required' => false
	            ])
	            ->add('enabled', CheckboxType::class, [
	            	'label' => 'email_templates.isEnabled',
	            	'attr' => ($options['mode'] == "create") ? ['checked' => 'checked'] : [],
	            	'required' => false
	            ])
	        ;
	    }

	    public function configureOptions(OptionsResolver $resolver)
		{
		    $resolver->setDefaults(array(
		    	'data_class' => 'ReaccionEstudio\ReaccionCMSBundle\Entity\EmailTemplate',
		        'mode' => 'create'
		    ));
		}
	}