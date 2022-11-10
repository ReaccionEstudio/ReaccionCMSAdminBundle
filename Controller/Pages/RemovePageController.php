<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages;

use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Loader\FileLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class RemovePageController extends AbstractController
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function index(Page $page)
    {
        $em = $this->getDoctrine()->getManager();

        if (empty($page)) {
            throw new NotFoundHttpException("Page content not found");
        }

        $pageName = $page->getName();

        try {
            // remove
            $em->remove($page);
            $em->flush();

            // TODO: create EVENT
            $this->get('reaccion_cms.router')->setLoader(FileLoader::class)->updateSchema();

            $this->addFlash('success', $this->translator->trans('page_form.remove_success_message', array('%name%' => $pageName)));

            return $this->redirectToRoute('reaccion_cms_admin_pages_index');
        } catch (\Exception $e) {
            $errorMssg = $this->translator->trans('page_form.remove_error_message', array('%name%' => $pageName, '%error%' => $e->getMessage()));
            $this->addFlash('error', $errorMssg);

            return $this->redirectToRoute(
                'reaccion_cms_admin_pages_detail',
                array('page' => $page->getId())
            );
        }
    }
}
