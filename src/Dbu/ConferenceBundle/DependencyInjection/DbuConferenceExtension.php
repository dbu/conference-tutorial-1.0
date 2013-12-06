<?php

namespace Dbu\ConferenceBundle\DependencyInjection;

use PHPCR\Util\PathHelper;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DbuConferenceExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter($this->getAlias() . '.home_path', $config['home_path']);

        // PathHelper::absolutize prepends home_path unless speakers_path is an absolute path already
        $speakerPath = PathHelper::absolutizePath($config['speakers_path'], $config['home_path']);
        $container->setParameter($this->getAlias() . '.speakers_path', $speakerPath);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
