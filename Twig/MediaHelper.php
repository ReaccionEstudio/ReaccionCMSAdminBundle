<?php

namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * MediaHelper class (Twig_Extension)
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class MediaHelper extends \Twig_Extension
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getImageResolution', array($this, 'getImageResolution')),
            new \Twig_SimpleFunction('convertBytesToKb', array($this, 'convertBytesToKb'))
        );
    }

    /**
     * Get image resolution
     *
     * @param String  $imagePath     Full image path
     */
    public function getImageResolution(String $imagePath) : String
    {
        $imagePath = $this->container->getParameter("reaccion_cms_admin.upload_dir") . $imagePath;

        if( ! file_exists($imagePath)) return 'undefined';

        list($width, $height) = getimagesize($imagePath);

        if(empty($width) || empty($height)) return 'undefined';

        return $width . ' x ' . $height;
    }

    public function convertBytesToKb(Float $bytes) : Float
    {
        return round(($bytes / 1024), 2);
    }

	public function getName()
    {
        return 'MediaHelper';
    }
}