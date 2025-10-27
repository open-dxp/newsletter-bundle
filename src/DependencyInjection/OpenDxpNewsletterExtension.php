<?php
declare(strict_types=1);

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PCL
 */

namespace OpenDxp\Bundle\NewsletterBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class OpenDxpNewsletterExtension extends ConfigurableExtension
{
    public function getAlias(): string
    {
        return 'opendxp_newsletter';
    }

    public function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../config')
        );

        $loader->load('services.yaml');
        $loader->load('message_handler.yaml');

        $container->setParameter('opendxp_newsletter', $mergedConfig);

        $this->configureAdapterFactories($container, $mergedConfig['source_adapters'], 'opendxp_newsletter.address_source_adapter.factories');
    }

    /**
     * Configure Adapter Factories
     */
    private function configureAdapterFactories(ContainerBuilder $container, array $factories, string $serviceLocatorId): void
    {
        $serviceLocator = $container->getDefinition($serviceLocatorId);
        $arguments = [];

        foreach ($factories as $key => $serviceId) {
            $arguments[$key] = new Reference($serviceId);
        }

        $serviceLocator->setArgument(0, $arguments);
    }
}
