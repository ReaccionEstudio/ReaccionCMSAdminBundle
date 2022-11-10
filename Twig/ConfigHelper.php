<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Twig;

use ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigService;

/**
 * ConfigHelper class (Twig_Extension)
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class ConfigHelper extends \Twig_Extension
{
    /**
     * @var ConfigService
     */
    private $config;

    public function __construct(ConfigService $config)
    {
        $this->config = $config;
    }

    /**
     * Constructor
     */
	public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getConfigValue', array($this, 'getConfigValue')),
            new \Twig_SimpleFunction('getLogoPath', array($this, 'getLogoPath')),
            new \Twig_SimpleFunction('printSerializedValueAsText', array($this, 'printSerializedValueAsText'))
        );
    }

    /**
     * Get config value
     *
     * @param   String   $key      Config key
     * @return  String   [type]    Config value
     */
    public function getConfigValue(String $key="") : String
    {
        if(empty($key)) return '';

        return $this->config->get($key);
    }

    /**
     * Get logo path from config parameter
     *
     * @return  String   [type]    Logo relative path
     */
    public function getLogoPath() : String
    {
        $logoPath = $this->getConfigValue("admin_logo");

        if(preg_match('/http/', $logoPath)){
            return $logoPath;
        }

        if($logoPath == "images/reaccion_cms_logo.jpg")
        {
            return 'build/' . $logoPath;
        }
        else
        {
            return 'uploads/' . $logoPath;
        }
    }

    /**
     * Print a serialized config parameter value as string
     *
     * @param   String      $value      Serialized value  
     * @return  String      $result     Text value  
     */
    public function printSerializedValueAsText(String $value) : String
    {
        $result = '';
        $array = unserialize($value);

        foreach($array as $key => $item)
        {
            $result .= $key . " = " . $item . ", ";
        }

        $result = substr($result, 0, strlen($result)-2);
        return $result;
    }

	public function getName() : String
    {
        return 'ConfigHelper';
    }
}
