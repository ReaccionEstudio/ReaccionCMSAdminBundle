<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Media;

	use Doctrine\ORM\EntityManager;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Configuration;

	/**
	 * Image manipulation size service
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class ResizeImageService
	{
		/**
		 * @var EntityManagerInterface
		 *
		 * EntityManager
		 */
		private $em;

		/**
		 * @var String
		 *
		 * Original image full path
		 */
		private $originalImagePath;

		/**
		 * @var String
		 *
		 * Original image basename
		 */
		private $imageBasename;

		/**
		 * @var Array 
		 *
		 * All image sizes defined in configuration entites
		 */
		private $imageSizesConfig;

		/**
		 * @var Array 
		 *
		 * Array with the resized images path
		 */
		private $resizedImages;

		/**
		 * Constructor
		 *
		 * @param EntityManager 	$em 	Entity manager
		 */
		public function __construct(EntityManager $em)
		{
			$this->em = $em;
			$this->resizedImages = array();
		}

		/** 
		 * Resize the original image to large, medium and small versions.
		 */
		public function resize(String $imagePath) : Array
		{
			$this->originalImagePath = $imagePath;
			$this->imageBasename = basename($this->originalImagePath);

			if( ! file_exists($this->originalImagePath) )
			{
				throw new \Exception("Image was not found in '" . $this->originalImagePath . "'.");
			}

			$this->imageSizesConfig = $this->getImageConfigSizes();
			
			if(empty($this->imageSizesConfig))
			{
				throw new \Exception("Images sizes were not found in any configuration entity.");
			}

			$this->resizeImage();

			return $this->resizedImages;
		}

		/**
		 * Resize image process
		 *
		 * @return void 	[type]
		 */
		private function resizeImage() : void
		{
			list($width, $height) = getimagesize($this->originalImagePath);

			foreach($this->imageSizesConfig as $configSize)
			{
				$configSizeValue = unserialize($configSize['value']);

				if($width < $configSizeValue['width'] && $height < $configSizeValue['height']) continue;

				try
				{
					$imageTypePrefix = $configSize['name'][0];
					$newImageBasename = $imageTypePrefix . '_' . $this->imageBasename;
					$newImagePath = str_replace($this->imageBasename, $newImageBasename, $this->originalImagePath);

					$image = new \Gumlet\ImageResize($this->originalImagePath);
					$image->resizeToHeight($configSizeValue['height']);
					$image->resizeToWidth($configSizeValue['width']);
					$image->save($newImagePath);

					$relativeNewImagePath = explode("uploads/", $newImagePath);
					$relativeNewImagePath = $relativeNewImagePath[1];
					
					$this->resizedImages[$imageTypePrefix] = array(
																'path' => $relativeNewImagePath,
																'size' => filesize($newImagePath)
															);
				}
				catch(\Exception $e)
				{
					// TODO: log error
					throw $e;
				}
			}
		}

		/**
		 * Get all image sizes defined in configuration entites
		 *
		 * @return Array 	[type]		Array with all image sizes configuration
		 */
		private function getImageConfigSizes() : Array
		{
			$configKeysToFind = ['large.image.size', 'medium.image.size', 'small.image.size'];
			
			$dql = "SELECT c.name, c.value 
					FROM  App\ReaccionEstudio\ReaccionCMSBundle\Entity\Configuration c
					WHERE c.name IN (:configKeys)";

			$query = $this->em->createQuery($dql)
								->setParameter("configKeys", $configKeysToFind);

			return $query->getResult();
		}
		
	}