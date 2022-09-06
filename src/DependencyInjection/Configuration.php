<?php

namespace HBM\MessengerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface {

  /**
   * {@inheritdoc}
   */
  public function getConfigTreeBuilder(): TreeBuilder {
    $treeBuilder = new TreeBuilder('hbm_messenger');
    $rootNode = $treeBuilder->getRootNode();

    $rootNode
      ->children()
        ->arrayNode('mailer')
          ->children()
            ->scalarNode('mails_per_second')->defaultNull()->end()
            ->arrayNode('from')
              ->children()
                ->scalarNode('mail')->end()
                ->scalarNode('name')->defaultValue('')->end()
              ->end()
            ->end()
            ->arrayNode('subject')
              ->children()
                ->scalarNode('prefix')->defaultNull()->end()
                ->scalarNode('postfix')->defaultNull()->end()
              ->end()
            ->end()
            ->arrayNode('headers')
              ->prototype('array')
                ->children()
                  ->scalarNode('key')->end()
                  ->scalarNode('value')->end()
                ->end()
              ->end()
            ->end()
            ->arrayNode('defaults')
              ->useAttributeAsKey('key')
              ->prototype('array')
                ->children()
                  ->scalarNode('key')->end()
                  ->scalarNode('value')->end()
                ->end()
              ->end()
            ->end()
          ->end()
        ->end()
      ->end();

    return $treeBuilder;
  }

}
