<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Theme;

	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigService;

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
		 * Constructor
		 */
		public function __construct(ConfigService $config)
		{
			$this->config = $config;
		}

		
	}