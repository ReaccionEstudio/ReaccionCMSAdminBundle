<?php

namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Twig;

use Services\Managers\ManagerPermissions;
use Symfony\Component\HttpFoundation\RequestStack;
use App\ReaccionEstudio\ReaccionCMSAdminBundle\Constants\Languages;
use App\ReaccionEstudio\ReaccionCMSBundle\Services\Language\LanguageService;

/**
 * Twig widgets helper class (Twig_Extension)
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class WidgetsHelper extends \Twig_Extension
{
    private $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getLanguagesWidgetData', array($this, 'getLanguagesWidgetData'))
        );
    }

    /**
     * Get the language widget data
     *
     * @return  Array  $languagesList     Widget data
     */
    public function getLanguagesWidgetData()
    {
        $languagesList = [];

        foreach(Languages::LANGUAGES as $language)
        {
            if($this->languageService->getLanguage() == $language)
            {
                $languagesList['default'] = [
                    'language' => $language,
                    'originalName' => (Languages::LANGUAGES_ORIGINAL_NAMES[$language]) ?? '',
                    'icon' => Languages::LANGUAGE_ICONS[$language]
                ];
            }
            else
            {
                $languagesList['languages'][] = [
                    'language' => $language,
                    'originalName' => (Languages::LANGUAGES_ORIGINAL_NAMES[$language]) ?? '',
                    'icon' => Languages::LANGUAGE_ICONS[$language]
                ];
            }

        }

        return $languagesList;
    }

	public function getName()
    {
        return 'WidgetsHelper';
    }
}