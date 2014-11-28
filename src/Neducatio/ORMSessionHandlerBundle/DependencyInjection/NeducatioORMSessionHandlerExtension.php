<?php
namespace Neducatio\ORMSessionHandlerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Reference;

/**
 * NeducatioORMSessionHandlerBundle
 *
 * @codeCoverageIgnore
 */
class NeducatioORMSessionHandlerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $processedConfiguration = $this->processConfiguration($configuration, $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container->getDefinition('neducatio_ormsessionhandler.repository_session')->addArgument($processedConfiguration['class']);
        $container->getDefinition('neducatio_ormsessionhandler.httpfoundation_session_storage_handler')->addArgument($processedConfiguration['writer']);



    }
}
