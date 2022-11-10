<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\EmailTemplates;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use ReaccionEstudio\ReaccionCMSBundle\Entity\EmailTemplate;
use ReaccionEstudio\ReaccionCMSAdminBundle\Form\EmailTemplates\EmailTemplateType;
use ReaccionEstudio\ReaccionCMSAdminBundle\DataTransformer\EmailTemplate\MessageParamsDataTransformer;
use Symfony\Contracts\Translation\TranslatorInterface;

class CreateEmailTemplateController extends AbstractController
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function index(Request $request)
    {
        $emailTemplate = new EmailTemplate();

        // form
        $formOptions = ['mode' => 'create'];
        $form = $this->createForm(EmailTemplateType::class, $emailTemplate, $formOptions);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            try {
                // build custom message params
                $messageParamsDataTransformer = new MessageParamsDataTransformer($request);
                $messageParams = $messageParamsDataTransformer->getMessageParamsAsJson();

                if ($messageParams != "[]") {
                    $emailTemplate->setMessageParams($messageParams);
                }

                // save
                $em->persist($emailTemplate);
                $em->flush();

                // success message
                $successMessage = $this->translator->trans('email_templates.create_success_message');
                $this->addFlash('success', $successMessage);

                return $this->redirectToRoute('reaccion_cms_admin_preferences_email_templates');
            } catch (\Exception $e) {
                $this->addFlash('error', $this->translator->trans('email_templates.create_error_message', array('%error%' => $e->getMessage())));
            }
        }

        return $this->render("@ReaccionCMSAdminBundle/emailTemplates/form.html.twig",
            [
                'form' => $form->createView(),
                'mode' => 'create'
            ]
        );
    }
}
