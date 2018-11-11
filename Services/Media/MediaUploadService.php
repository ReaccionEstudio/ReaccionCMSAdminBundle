<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Media;

	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use Symfony\Component\HttpFoundation\File\UploadedFile;
	use Doctrine\ORM\EntityManager;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Media;

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
		 * Full path for the uploaded file
		 * @var String
		 */
		private $uploadPath;

		/**
		 * File to upload
		 * @var Symfony\Component\HttpFoundation\File\UploadedFile
		 */
		private $file;

		/**
		 * Constructor
		 */
		public function __construct(EntityManager $em, String $uploadPath)
		{
			$this->em = $em;
			$this->uploadPath = $uploadPath;
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

			// TODO: check mimetype

			try
			{
				// move file to folder
				$this->file->move($this->uploadPath, $filename);

				// create media entity
				$filepath = $this->uploadPath . "/" . $filename;
				$this->createMediaEntity($originalFilename, $filepath, $mimeType, $size);
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
		 * @return 	void 		
		 */
		private function createMediaEntity(String $originalFilename, String $filepath, String $mimeType, Float $size) : void
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