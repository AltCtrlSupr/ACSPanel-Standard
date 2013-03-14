<?php

namespace ACS\ACSPanelSettingsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ACSACSPanelSettingsExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('acs_settings.home_base',$config['home_base']);
        $container->setParameter('acs_settings.setting_class',$config['setting_class']);
        $container->setParameter('acs_settings.settings_class',$config['setting_class']);
        $container->setParameter('acs_settings.user_fields',$config['user_fields']);
        $container->setParameter('acs_settings.system_fields',$config['system_fields']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
