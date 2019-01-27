<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Twig;

use ReaccionEstudio\ReaccionCMSBundle\Entity\Media;

/**
 * MediaHelper class (Twig_Extension)
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class MediaHelper extends \Twig_Extension
{
    /** 
     * @var String
     */
    private $upload_dir;

    /**
     * Constructor
     */
    public function __construct(String $upload_dir)
    {
        $this->upload_dir = $upload_dir;
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
     * @param  String  $imagePath     Full image path
     * @return String  [type]         Image resolution
     */
    public function getImageResolution(String $imagePath) : String
    {
        $imagePath = $this->upload_dir . $imagePath;

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