<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Theme;

	use Symfony\Component\Yaml\Yaml;
	use Symfony\Component\Yaml\Exception\ParseException;

	class ListInstalledThemesService
	{
		/**
		 * @var Array
		 *
		 * Full theme paths
		 */
		private $themesPaths;

		/**
		 * @var Array
		 *
		 * Available themes
		 */
		private $availableThemes = array();

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
		 * @return Array 	$this->availableThemes 		Installed themes
		 */
		public function listAllThemes() : Array
		{
			foreach($this->themesPaths as $themePath)
			{
				if(!file_exists($themePath)) continue;

				$files = scandir($themePath);
				$this->getThemesFromPath($files, $themePath);
			}

			return $this->availableThemes;
		}

		/**
		 * Get themes from specified path
		 *
		 * @param 	Array 	$pathFiles 		Path files
		 * @param 	String 	$themePath 		Full theme path
		 * @return 	void 	[type]
		 */
		private function getThemesFromPath(Array $pathFiles, String $themePath) : void
		{
			foreach($pathFiles as $file)
			{
				if($file == "." || $file == "..") continue;

				// theme config file
				$configThemeFile = $themePath . "/" . $file . "/config.yaml";

				if(!file_exists($configThemeFile)) continue;
				
				$themeInfo = $this->loadConfigFile($configThemeFile);

				if(empty($themeInfo['theme_config'])) continue;

				$themeConfig = $themeInfo['theme_config'];

				$this->availableThemes[] = [
					'name' => $themeConfig['name'],
					'description' => $themeConfig['name'],
					'version' => $themeConfig['version'],
					'author' => $themeConfig['author'],
					'website' => $themeConfig['website'],
					'preview_image' => $themeConfig['preview_image'],
					'folder_name' => $themeConfig['folder_name']
				];
			}
		}

		/**
		 * Load template config.yaml file
		 *
		 * @param  String 	$configFilePath		Full config file path
		 * @return Array 	[type] 				Config file parameters
		 */
		private function loadConfigFile(String $configFilePath) : Array
		{
			if( ! file_exists($configFilePath) ) return [];
			return Yaml::parseFile($configFilePath);
		}
	}