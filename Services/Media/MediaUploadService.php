<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Media;

	use Doctrine\ORM\EntityManager;
	use Symfony\Component\HttpFoundation\File\UploadedFile;
	use Symfony\Component\Translation\TranslatorInterface;

	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Media;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Media\ResizeImageService;

	/**
	 * Service for managing media file uploads.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class MediaUploadService
	{
		/**
		 * @var EntityManagerInterface
		 *
		 * EntityManager
		 */
		private $em;

		/**
		 * @var TranslatorInterface
		 *
		 * Translator
		 */
		private $translator;

		/**
		 * @var ResizeImageService
		 *
		 * Resize image service
		 */
		private $resizeImageService;

		/**
		 * Full path for the uploaded file
		 * @var String
		 */
		private $uploadPath;

		/**
		 * Collection with allowed file mimeTypes
		 * @var Array
		 */
		private $allowedMimeTypes;

		/**
		 * File to upload
		 * @var Symfony\Component\HttpFoundation\File\UploadedFile
		 */
		private $file;

		/**
		 * Constructor
		 */
		public function __construct(EntityManager $em, TranslatorInterface $translator, ResizeImageService $resizeImageService, String $uploadPath, Array $allowedMimeTypes)
		{
			$this->em = $em;
			$this->translator = $translator;
			$this->resizeImageService = $resizeImageService;
			$this->uploadPath = $uploadPath;
			$this->allowedMimeTypes = $allowedMimeTypes;
			$this->createUploadFolder();
		}

		/**
		 * Upload media file
		 *
		 * @param 	UploadedFile 	$file 			File object
		 * @return 	String 			$filepath		File path
		 */
		public function upload(UploadedFile $file) : void
		{
			$this->file = $file;

			$originalFilename = $this->file->getClientOriginalName();
			$mimeType = $this->file->getClientMimeType();
			$extension = $this->file->getClientOriginalExtension();
			$filename = md5(uniqid()) . "." . $extension;
			$size = $this->file->getSize();

			// check if file has valid mimeType
			if( ! in_array($mimeType, $this->allowedMimeTypes))
			{
				$invalidMssg =  $this->translator->trans(
									'media_create.invalid_mimetype', 
									array(
										'%filename%' => $originalFilename, 
										'%mimeType%' => $mimeType, 
										'%validTypes%' => implode(", ", $this->allowedMimeTypes)
									)
								);
				throw new \Exception($invalidMssg);
			}

			try
			{
				// full filepath
				$filepath = $this->uploadPath . "/" . $filename;

				// move file to folder
				$this->file->move($this->uploadPath, $filename);

				// resize image
				$resizedImages = $this->resizeImageService->resize($filepath);

				// create media entity
				$this->createMediaEntity($originalFilename, $filepath, $mimeType, $size, $resizedImages);
			}
			catch(\Exception $e)
			{
				// TODO: log error
				throw $e;
			}
		}

		/**
		 * Create Media entity for the uploaded file
		 * 
		 * @param 	String 		$originalFilename 		Original filename
		 * @param 	String 		$filepath 				Uploadad file full path
		 * @param 	String 		$mimeType 				File mimeType
		 * @param 	Float 		$size 					File size in bytes
		 * @param 	Array 		$$resizedImages 		Resized images info
		 * @return 	void 		
		 */
		private function createMediaEntity(String $originalFilename, String $filepath, String $mimeType, Float $size, Array $resizedImages) : void
		{
			$filepathArray = explode("uploads/", $filepath);
			$filepath = $filepathArray[1];

			try
			{
				$media = new Media();
				$media->setSize($size);
				$media->setName($originalFilename);
				$media->setPath($filepath);
				$media->setMimetype($mimeType);

				if( ! empty($resizedImages['l']['path']))
				{
					$media->setLargePath($resizedImages['l']['path']);
					$media->setLargeSize($resizedImages['l']['size']);
				}

				if( ! empty($resizedImages['m']['path']))
				{
					$media->setMediumPath($resizedImages['m']['path']);
					$media->setMediumSize($resizedImages['m']['size']);
				}

				if( ! empty($resizedImages['s']['path']))
				{
					$media->setSmallPath($resizedImages['s']['path']);
					$media->setSmallSize($resizedImages['s']['size']);
				}

				$this->em->persist($media);
				$this->em->flush();
			}	
			catch(\Exception $e)
			{
				// TODO: log error
				throw $e;
			}
		}

		/**
		 * Create the upload folder for file upload
		 */
		private function createUploadFolder() : void
		{
			$currentDate = ( new \DateTime() )->format("Y/m/d");
			$this->uploadPath = $this->uploadPath . $currentDate;

			if( ! file_exists($this->uploadPath) )
			{
				try
				{
					mkdir($this->uploadPath, 0755, true);
				}
				catch(\Exception $e)
				{
					// TODO: log error
					throw $e;
				}
			}
		}
	}