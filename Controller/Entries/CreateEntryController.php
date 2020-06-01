<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Entries;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
use ReaccionEstudio\ReaccionCMSAdminBundle\Form\Entries\EntryType;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class CreateEntryController
 * @package ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Entries
 */
class CreateEntryController extends Controller
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * CreateEntryController constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $entry = new Entry();

        // form
        $form = $this->createForm(EntryType::class, $entry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            try {
                if($defaultImage = $form['defaultImageFile']->getData()){
                    // upload process
                    $mediaUploadService = $this->get("reaccion_cms_admin.media_upload");
                    $mediaUploadService->upload($defaultImage);
                    $media = $mediaUploadService->getMedia();

                    if($media) {
                        $entry->setDefaultImage($media);
                    }
                }

                // generate resume
                $this->get("reaccion_cms_admin.entry")->generateResume($entry);

                // generate slug
                if(empty($entry->getSlug())) {
                    $entry->setSlug($entry->getName());
                }

                // save
                $em->persist($entry);
                $em->flush();

                // success message
                $successMessage = $this->translator->trans('entries_form.create_success_form');
                $this->addFlash('success', $successMessage);

                return $this->redirectToRoute('reaccion_cms_admin_entries_list');
            } catch (\Exception $e) {
                $this->addFlash('error', $this->translator->trans('entries_form.create_error_form', array('%error%' => $e->getMessage())));
            }
        }

        return $this->render("@ReaccionCMSAdminBundle/entries/form.html.twig",
            [
                'form' => $form->createView(),
                'mode' => 'create',
                'entry' => null
            ]
        );
    }
}
