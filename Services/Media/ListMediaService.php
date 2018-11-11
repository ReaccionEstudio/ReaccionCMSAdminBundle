<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Media;

	/**
	 * Service for media listing.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class ListMediaService
	{
		/**
		 * Full path for file uploads
		 * @var String
		 */
		private $uploadPath;

		/**
		 * Constructor
		 */
		public function __construct(String $uploadPath)
		{
			$this->uploadPath = $uploadPath;
		}

		/**
		 * Get media list
		 *
		 * @param 	Integer 	$page 				Current listing page
		 * @return 	Array 		$mediaFilesArray 	Found files
		 */
		public function getList(Int $page=1) : Array
		{
			$mediaFiles = $this->getDirContents($this->uploadPath);

			$mediaFilesArray = array();
			
			foreach($mediaFiles as $filePath)
			{
				$mediaFilesArray[] = array(
										'path' => $filePath,
										'name' => basename($filePath)
									 );
			}

			// TODO: paginate results

			return $mediaFilesArray;
		}

		/**
		 * Get all files from specified directory
		 *
		 * @param 	String 		$dir 		Directory where to search
		 * @param 	Array 		$results 	Found files
		 * @return 	Array 		$results 	Found files
		 */
		private function getDirContents(String $dir, Array &$results = array()) : Array
		{
		    $files = scandir($dir);

		    foreach($files as $key => $value)
		    {
		        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);

		        if( ! is_dir($path)) 
		        {
		        	$filePath = explode("public/", $path);
		            $results[] = $filePath[1];
		        } 
		        else if($value != "." && $value != "..") 
		        {
		            $this->getDirContents($path, $results);
		        }
		    }

		    return $results;
		}

	}