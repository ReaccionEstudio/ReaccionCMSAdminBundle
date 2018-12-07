<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Theme;

	class ListInstalledThemesService
	{
		/**
		 * @var Array
		 *
		 * Full theme paths
		 */
		private $themesPaths;

		/**
		 * Constructor
		 */
		public function __construct(Array $themesPaths)
		{
			$this->themesPaths = $themesPaths;
		}

		/** 
		 * List all installed themes
		 *
		 * @return Array 	[type] 		Installed themes
		 */
		public function listAllThemes() : Array
		{
			foreach($this->themesPaths as $themePath)
			{
				if(!file_exists($themePath)) continue;

				$files = scandir($themePath);
				var_dump($files);
			}

			return [];
		}
	}