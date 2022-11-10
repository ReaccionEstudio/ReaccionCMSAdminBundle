<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Configuration\Mailer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Configuration;
use Symfony\Contracts\Translation\TranslatorInterface;

class SendTestEmailController extends AbstractController
{
    private $translator;

    /**
     * Constructor
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function index(Request $request)
    {
        $result = $this->get("reaccion_cms.mailer")->sendTestEmail();

        if ($result) {
            $successMessage = $this->translator->trans('preferences_mailer.sent_test_email_successfully');
            $this->addFlash('success', $successMessage);
        }

        return $this->redirectToRoute('reaccion_cms_admin_preferences_mailer');
    }
}
