<?php

namespace HBM\MessengerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class HBMMessengerExtension extends Extension {

  /**
   * {@inheritdoc}
   */
  public function load(array $configs, ContainerBuilder $container) {
    $configuration = new Configuration();
    $config = $this->processConfiguration($configuration, $configs);

    $container->setParameter('hbm.messenger', $config);
    $container->setParameter('hbm.messenger.mailer', $config['mailer']);

    $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
    $loader->load('services.yaml');
  }
}
