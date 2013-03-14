<?php

namespace ACS\ACSPanelSettingsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('acs_settings');
        $rootNode->children()->scalarNode('home_base')
            ->defaultValue('/home/')
            ->isRequired()
            ->cannotBeEmpty()
        ->end();
        $rootNode->children()->scalarNode('setting_class')
            ->defaultValue('ACS\ACSPanelBundle\Entity\PanelSetting')
            ->isRequired()
            ->cannotBeEmpty()
        ->end();
        $rootNode->children()->scalarNode('settings_class')
            ->defaultValue('ACS\ACSPanelSettingsBundle\Doctrine\SettingManager')
            ->isRequired()
            ->cannotBeEmpty()
        ->end();

        $rootNode->children()->arrayNode('user_fields')
            ->requiresAtLeastOneElement()
            ->prototype('array')
                ->children()
                    ->scalarNode('setting_key')
                        //->isRequired(true)
                    ->end()
                    ->scalarNode('label')
                        //->isRequired(true)
                    ->end()
                    ->scalarNode('field_type')
                        //->defaultValue(true)
                    ->end()
                    ->scalarNode('default_value')
                        //->defaultValue(true)
                    ->end()
                    ->scalarNode('context')
                        //->defaultValue(true)
                    ->end()
                ->end()
            ->end()
        ->end();

        $rootNode->children()->arrayNode('system_fields')
            ->requiresAtLeastOneElement()
            ->prototype('array')
                ->children()
                    ->scalarNode('setting_key')
                        //->isRequired(true)
                    ->end()
                    ->scalarNode('label')
                        //->isRequired(true)
                    ->end()
                    ->scalarNode('field_type')
                        //->defaultValue(true)
                    ->end()
                    ->scalarNode('default_value')
                        //->defaultValue(true)
                    ->end()
                    ->scalarNode('context')
                        //->defaultValue(true)
                    ->end()
                ->end()
            ->end()
        ->end();



        return $treeBuilder;
    }
}
