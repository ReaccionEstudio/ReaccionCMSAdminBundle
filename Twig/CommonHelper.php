<?php

namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Twig;

use Services\Managers\ManagerPermissions;
use App\ReaccionEstudio\ReaccionCMSAdminBundle\Constants\Languages;

/**
 * CommonHelper class (Twig_Extension)
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class CommonHelper extends \Twig_Extension
{
	public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('unserialize', array($this, 'unserialize')),
            new \Twig_SimpleFunction('getFlagIconForLanguage', array($this, 'getFlagIconForLanguage')),
        );
    }

    /**
     * Unserialize an array
     *
     * @param   String   $data     Serialized array
     * @return  Array    [type]    Unserialized array
     */
    public function unserialize($data)
    {
        return unserialize($data);
    }

    /**
     * Get flag icon class for specified language
     *
     * @param  String   $lang       Language
     * @return String   [type]      Flag icon name
     */
    public function getFlagIconForLanguage($lang)
    {
        if(isset(Languages::LANGUAGE_ICONS[$lang]))
        {
            return Languages::LANGUAGE_ICONS[$lang];
        }
        
        return 'gb';
    }

	public function getName()
    {
        return 'CommonHelper';
    }
}