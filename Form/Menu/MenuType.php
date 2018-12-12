<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Menu;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

	use Doctrine\ORM\EntityRepository;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Constants\Languages;
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
	            ->add('language', ChoiceType::class, [
	            	'label' => 'users_form.language',
	            	'choices' => Languages::LANGUAGES,
	            	'choice_label' => function($choiceValue, $key, $value)
                    {
                    	return 'languages.' . $value;
                    },
	            	'choice_value' => function($value)
                    {
                    	return $value;
                    },
	            	'required' => false,
	            	'attr' => ['class' => 'selectize']
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
		    	'data_class' => 'App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu',
		        'mode' => 'create'
		    ));
		}
	}