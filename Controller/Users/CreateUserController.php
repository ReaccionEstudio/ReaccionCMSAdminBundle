<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Users;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\User;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Users\UserType;
	use Symfony\Component\Translation\TranslatorInterface;

	class CreateUserController extends Controller
	{
		public function index(Request $request, TranslatorInterface $translator)
		{
			$user = new User();
			$em = $this->getDoctrine()->getManager();

			// form parameters
			$userRoles = $this->getParameter("reaccion_cms_admin.roles");

			// form
			$form = $this->createForm(UserType::class, $user, ['roles' => $userRoles]);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				try
				{
					// save
					$em->persist($user);
					$em->flush();

					$this->addFlash('success', $translator->trans('users_form.create_success_message') );
					
					return $this->redirectToRoute('reaccion_cms_admin_users_index');
				}
				catch(\Exception $e)
				{
					$this->addFlash(
						'error', 
						$translator->trans('users_form.create_error_message', [
							'%name%' => $form['username']->getData(),
							'%error%' => $e->getMessage()
						]) 
					);
				}
			}

			return $this->render("@ReaccionCMSAdminBundle/users/form.html.twig",
				[
					'form' => $form->createView()
				]
			);
		}
	}