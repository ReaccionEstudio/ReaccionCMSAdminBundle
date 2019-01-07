<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Configuration\Mailer;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Configuration;

	class SendTestEmailController extends Controller
	{
		public function index(Request $request, TranslatorInterface $translator)
		{
			$result = $this->get("reaccion_cms.mailer")->sendTestEmail();

			if($result)
			{
				$successMessage = $translator->trans('preferences_mailer.sent_test_email_successfully');
				$this->addFlash('success', $successMessage);
			}

			return $this->redirectToRoute('reaccion_cms_admin_preferences_mailer');
		}
	}