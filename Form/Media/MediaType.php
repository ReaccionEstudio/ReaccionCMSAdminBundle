<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Media;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Form\Extension\Core\Type\FileType;

	class MediaType extends AbstractType
	{
	    public function buildForm(FormBuilderInterface $builder, array $options)
	    {
	        $builder
	            ->add('attachment', FileType::class, [
	            	'label' => 'media_create.file',
	            	'required' => true
	            ])
	        ;
	    }
	}