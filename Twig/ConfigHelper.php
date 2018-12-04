<?php

namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * ConfigHelper class (Twig_Extension)
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class ConfigHelper extends \Twig_Extension
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

	public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getConfigValue', array($this, 'getConfigValue'))
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

        $configService = $this->container->get("reaccion_cms.config");
        return $configService->get($key);
    }

	public function getName()
    {
        return 'ConfigHelper';
    }
}