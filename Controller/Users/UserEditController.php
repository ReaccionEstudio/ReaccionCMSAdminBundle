<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Users;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\User;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Form\Users\UserType;
	use Symfony\Component\Translation\TranslatorInterface;

	class UserEditController extends Controller
	{
		private $translator;
		
		public function __construct(TranslatorInterface $translator)
		{
			$this->translator = $translator;	
		}

		public function index(User $user, Request $request)
		{
			$em = $this->getDoctrine()->getManager();

			// form parameters
			$userRoles = $this->getParameter("reaccion_cms_admin.roles");

			// form
			$form = $this->createForm(UserType::class, $user, [ 'roles' => $userRoles, 'mode' => 'edit' ]);

			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				try
				{
					// check if password has been modified
					$password = $form['userPassword']->getData();

					if($password)
					{
						$user->setPlainPassword($password);
					}

					// save
        			$userManager = $this->container->get('fos_user.user_manager');
        			$userManager->updateUser($user, true);

					$this->addFlash('success', $this->translator->trans('users_form.update_success_message') );
					
					return $this->redirectToRoute('reaccion_cms_admin_users_index');
				}
				catch(\Exception $e)
				{
					$this->addFlash(
						'error', 
						$this->translator->trans('users_form.update_error_message', [
							'%name%' => $form['username']->getData(),
							'%error%' => $e->getMessage()
						]) 
					);
				}
			}

			return $this->render("@ReaccionCMSAdminBundle/users/form.html.twig",
				[
					'form' => $form->createView(),
					'mode' => 'edit'
				]
			);
		}
	}