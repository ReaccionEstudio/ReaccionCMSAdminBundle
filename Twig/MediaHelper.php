<?php

namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Media;

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
            new \Twig_SimpleFunction('convertBytesToKb', array($this, 'convertBytesToKb')),
            new \Twig_SimpleFunction('getSmallestImageFromMedia', array($this, 'getSmallestImageFromMedia'))
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

    /**
     * Convert bytes to kilobytes
     * 
     * @param   Float    $bytes    Number of bytes
     * @return  Float    [type]    Kilobytes
     */
    public function convertBytesToKb(Float $bytes) : Float
    {
        return round(($bytes / 1024), 2);
    }

    /**
     * Get smallest image for Media entity
     *
     * @param   Media     $media      Media entity
     * @return  String    [type]      Media path
     */
    public function getSmallestImageFromMedia(Media $media) : String
    {
        if($media->getSmallPath()) return $media->getSmallPath();
        if($media->getMediumPath()) return $media->getMediumPath();
        if($media->getLargePath()) return $media->getLargePath();
        return $media->getPath();
    }

	public function getName()
    {
        return 'MediaHelper';
    }
}