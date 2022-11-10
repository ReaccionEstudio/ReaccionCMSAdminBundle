<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages\Content;

use Symfony\Component\HttpFoundation\Request;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
use Symfony\Contracts\Translation\TranslatorInterface;
use ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\PageContentType;

class CreatePageContentController extends AbstractController
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function index(Page $page, Request $request)
    {
        $pageContent = new PageContent();
        $em = $this->getDoctrine()->getManager();

        // form
        $form = $this->createForm(PageContentType::class, $pageContent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // set page for content
                $pageContent->setPage($page);

                // set content position
                $nextPosition = $this->get("reaccion_cms_admin.page_content_position")->getNextPosition($page);
                $pageContent->setPosition($nextPosition);

                // generate slug
                $pageContent->setSlug($pageContent->getName());

                // save
                $em->persist($pageContent);
                $em->flush();

                // update page type if necessary
                $this->get("reaccion_cms_admin.page")->setPageTypeByPageContent($pageContent);

                // flash message
                $successMessage = $this->translator->trans('page_content_form.create_success_message');
                $this->addFlash('success', $successMessage);

                return $this->redirectToRoute(
                    'reaccion_cms_admin_pages_detail',
                    array('page' => $page->getId())
                );
            } catch (\Exception $e) {
                $errorMssg = $this->translator->trans(
                    'page_content_form.create_error_message',
                    [
                        '%name%' => $pageContent->getName(),
                        '%error%' => $e->getMessage()
                    ]
                );
                $this->addFlash('error', $errorMssg);
            }
        }

        return $this->render("@ReaccionCMSAdminBundle/pages/content/create.html.twig",
            [
                'page' => $page,
                'form' => $form->createView()
            ]
        );
    }
}
