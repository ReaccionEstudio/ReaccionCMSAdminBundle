<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Media;

	use Doctrine\ORM\EntityManagerInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use Symfony\Component\HttpFoundation\File\UploadedFile;

	/**
	 * Service for managing media file uploads.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class UploadService
	{
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
		public function __construct(UploadedFile $file, String $uploadPath)
		{
			$this->file = $file;
			$this->uploadPath = $uploadPath;
			$this->createUploadFolder();
		}

		/**
		 * Upload media file
		 *
		 * @param 	File 		$file 				File object
		 * @return 	String 		$filepath 			File path
		 */
		public function upload() : void
		{
			$originalFilename = $this->file->getClientOriginalName();
			$filename = md5(uniqid());
			$mimeType = $this->file->getClientMimeType();
			$fullFilename = $filename;

			// TODO: check mimetype

			try
			{
				// TODO: Add file to database
				$this->file->move($this->uploadPath, $fullFilename);
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