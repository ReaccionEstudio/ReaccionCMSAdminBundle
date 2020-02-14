<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\PageType;
use ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\SeoPageType;
use ReaccionEstudio\ReaccionCMSBundle\Services\Themes\ThemeConfigService;

class PageDetailController extends Controller
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Constructor
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function index(Page $page, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // get current template views
        $themeFullPath = $this->get("reaccion_cms.theme")->getFullTemplatePath();
        $themeViews = (new ThemeConfigService($themeFullPath))->getViews();

        // forms
        $pageForm = $this->createForm(
            PageType::class,
            $page,
            [
                'templateViews' => $themeViews,
                'entity_manager' => $em,
                'query' => [
                    'translationGroup' => $page->getTranslationGroup()
                ]
            ]
        );
        $seoPageForm = $this->createForm(SeoPageType::class, $page);

        // handle forms request
        $pageForm->handleRequest($request);
        $seoPageForm->handleRequest($request);

        // update page entity for both forms submitted data
        if (
            ($pageForm->isSubmitted() && $pageForm->isValid()) ||
            ($seoPageForm->isSubmitted() && $seoPageForm->isValid())
        ) {
            try {
                $language = $pageForm['language']->getData();

                if ($pageForm['mainPage']->getData() == true && $page->isMainPage() == false) {
                    $this->get("reaccion_cms_admin.page")->resetMainPage($language);
                }

                // save
                $em->persist($page);
                $em->flush();

                // success message
                $this->addFlash('success', $this->translator->trans('page_form.update_success_message'));

            } catch (\Exception $e) {
                $errMssg = $this->translator->trans("page_form.update_error_message", array('%error%' => $e->getMessage() . "<br /><br />" . $e->getTraceAsString()));
                $this->addFlash('error', $errMssg);
            }
        }

        return $this->render("@ReaccionCMSAdminBundle/pages/detail.html.twig",
            [
                'page' => $page,
                'pageForm' => $pageForm->createView(),
                'seoPageForm' => $seoPageForm->createView()
            ]
        );
    }
}
