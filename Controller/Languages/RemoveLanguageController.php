<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Languages;

use ReaccionEstudio\ReaccionCMSBundle\Manager\LanguageManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\TranslatorInterface;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Language;

/**
 * Class RemoveLanguageController
 * @package ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Languages
 */
class RemoveLanguageController extends Controller
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var LanguageManager
     */
    private $languageManager;

    /**
     * RemoveLanguageController constructor.
     * @param TranslatorInterface $translator
     * @param LanguageManager $languageManager
     */
    public function __construct(TranslatorInterface $translator, LanguageManager $languageManager)
    {
        $this->translator = $translator;
        $this->languageManager = $languageManager;
    }

    /**
     * @param Language $language
     * @param Request $request
     * @return mixed
     */
    public function index(Language $language, Request $request)
    {
        if(empty($language))
        {
            throw new NotFoundHttpException("Menu not found");
        }

        $name = $language->getName();

        try
        {
            $this->languageManager->remove($language);

            // flash message
            $this->addFlash('success', $this->translator->trans('languages_form.remove_success_message', array('%name%' => $name)) );
        }
        catch(\Exception $e)
        {
            $errorMssg = $this->translator->trans('languages_form.remove_error_message', array('%name%' => $name, '%error%' => $e->getMessage()));
            $this->addFlash('error', $errorMssg);
        }

        return 	$this->redirectToRoute('reaccion_cms_admin_languages_index');
    }
}
