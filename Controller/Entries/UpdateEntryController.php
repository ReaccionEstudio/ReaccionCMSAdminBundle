<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Entries;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
use ReaccionEstudio\ReaccionCMSAdminBundle\Form\Entries\EntryType;
use Symfony\Component\Translation\TranslatorInterface;

class UpdateEntryController extends Controller
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function index(Entry $entry, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if($request->query->get('removeDefaultImage') === '1'){
            $entry->setDefaultImage(null);
            try{
                $em->persist($entry);
                $em->flush();

                $mssg = $this->translator->trans('crud.record_was_deleted_ok');
                $this->addFlash('success', $mssg);
            }catch (\Exception $e){
                $mssg = $this->translator->trans('crud.record_was_deleted_ko', array('%error%' => $e->getMessage()));
                $this->addFlash('success', $mssg);
            }

            return $this->redirectToRoute('reaccion_cms_admin_entries_update', ['entry' => $entry->getId()]);
        }

        // form
        $form = $this->createForm(EntryType::class, $entry, ['mode' => 'edit']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($defaultImage = $form['defaultImageFile']->getData()){
                // upload process
                $mediaUploadService = $this->get("reaccion_cms_admin.media_upload");
                $mediaUploadService->upload($defaultImage);
                $media = $mediaUploadService->getMedia();

                if($media) {
                    $entry->setDefaultImage($media);
                }
            }

            try {
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
                $successMessage = $this->translator->trans('entries_form.update_success_form');
                $this->addFlash('success', $successMessage);

                return $this->redirectToRoute('reaccion_cms_admin_entries_update', ['entry' => $entry->getId()]);
            } catch (\Exception $e) {
                $this->addFlash('error', $this->translator->trans('entries_categories_form.update_error_form', array('%error%' => $e->getMessage())));
            }
        }

        return $this->render("@ReaccionCMSAdminBundle/entries/form.html.twig",
            [
                'form' => $form->createView(),
                'mode' => 'edit',
                'entryName' => $form['name']->getData(),
                'entry' => $entry
            ]
        );
    }
}
