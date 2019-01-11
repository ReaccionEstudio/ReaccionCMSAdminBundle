<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\DataTransformer\EmailTemplate;

	use Symfony\Component\HttpFoundation\Request;

	/**
	 * Message params DataTransformer
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	final class MessageParamsDataTransformer
	{
		/**
		 * @var Array
		 * 
		 * Form data
		 */
		private $formData;

		/**
		 * @var Array
		 * 
		 * Message params
		 */
		private $messageParams = [];

		/**
		 * Constructor
		 */
		public function __construct(Request $request)
		{
			$this->formData = $request->request->all();
			$this->transform();
		}

		/**
		 * Transform data
		 */
		public function transform()
		{
			if( ! isset($this->formData['custom_params'])) return;

			foreach($this->formData['custom_params'] as $custom_param)
			{
				if( ! strlen($custom_param['name']) || ! strlen($custom_param['value']) ) continue;

				$this->messageParams[] = $custom_param;
			}
		}

		/**
		 * Get messageParams value as JSON
		 *
		 * @return String 	
		 */
		public function getMessageParamsAsJson() : String
		{
			return json_encode($this->messageParams);
		}
	}