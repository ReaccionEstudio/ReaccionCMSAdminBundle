<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Theme;

	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigService;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Theme\ListInstalledThemesService;

	/**
	 * Theme service
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class ThemeService
	{
		/**
		 * @var ConfigService
		 *
		 * Configuration service
		 */
		private $config;

		/**
		 * @var Array
		 *
		 * All theme full paths
		 */
		private $themesPaths;

		/**
		 * Constructor
		 */
		public function __construct(ConfigService $config, String $projectDir)
		{
			$this->config = $config;
			$this->projectDir = $projectDir;

			$this->getTemplatesPaths();
		}

		/**
		 * Get full templates paths
		 */
		private function getTemplatesPaths() : void
		{
			$this->themesPaths = [
				$this->projectDir . "/" . $this->config->get("themes_path")
			];
		}

		/** 
		 * List all installed themes
		 *
		 * @return Array 	[type] 		Installed themes
		 */
		public function listInstalledThemes() : Array
		{
			$listInstalledThemesService = new ListInstalledThemesService($this->themesPaths);
			return $listInstalledThemesService->listAllThemes();
		}
	}