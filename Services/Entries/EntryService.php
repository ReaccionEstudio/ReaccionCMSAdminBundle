<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Entries;

	use Doctrine\ORM\EntityManagerInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigServiceInterface;

	/**
	 * Entry service.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class EntryService
	{
		/**
		 * @var ConfigServiceInterface
		 *
		 * Config service
		 */
		private $config;

		/**
		 * Constructor
		 */
		public function __construct(ConfigServiceInterface $config)
		{
			$this->config = $config;
		}

		/**
		 * Generate entry resume
		 *
		 * 
		 */
		public function generateResume(Entry $entry) : Entry
		{
			// get words limit
			$limit = $this->config->get("entries_resume_characters_length");
			$limit = ( ! $limit || ! is_numeric($limit) || $limit < 0) ? 20 : $limit;
			$content = $entry->getContent();

			// clear content
			$content = strip_tags($content);

			// get resume
			if(strlen($content) > $limit)
			{
				$resume = substr($content, 0, strrpos(substr($content, 0, $limit), " ")) . " ...";
			}
			else
			{
				$resume = $content;
			}

			// set resume to entry object
			$entry->setResume($resume);

			return $entry;
		}
	}