<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\EntriesCategories;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\EntryCategory;

	class EntryCategoryType extends AbstractType
	{
	    public function buildForm(FormBuilderInterface $builder, array $options)
	    {
	        $builder
	            ->add('name', TextType::class, [
	            	'label' => 'menu_form.name',
	            	'required' => true
	            ])
	            ->add('enabled', CheckboxType::class, [
	            	'label' => 'menu_form.is_enabled',
	            	'attr' => ($options['mode'] == "create") ? ['checked' => 'checked'] : [],
	            	'required' => false
	            ])
	        ;
	    }

	    public function configureOptions(OptionsResolver $resolver)
		{
		    $resolver->setDefaults(array(
		    	'data_class' => 'App\ReaccionEstudio\ReaccionCMSBundle\Entity\EntryCategory',
		        'mode' => 'create'
		    ));
		}
	}