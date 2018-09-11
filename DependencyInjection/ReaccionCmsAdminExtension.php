<?php

namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Processor;

class ReaccionCmsAdminExtension extends Extension
{
	/**
     * Build the extension services
     *
     * @param array $configs
     * @param ContainerBuilder $container
     */
	public function load(array $configs, ContainerBuilder $container)
	{
		$processor = new Processor();

		$loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('reaccion_cms_admin.xml');

        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        
	}
}