<?php
declare(strict_types=1);

/**
 * OpenDXP
 *
 * This source file is licensed under the GNU General Public License version 3 (GPLv3).
 *
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) Pimcore GmbH (https://pimcore.com)
 * @copyright  Modification Copyright (c) OpenDXP (https://www.opendxp.ch)
 * @license    https://www.gnu.org/licenses/gpl-3.0.html  GNU General Public License version 3 (GPLv3)
 */

namespace OpenDxp\Bundle\NewsletterBundle\DependencyInjection\Compiler;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @internal
 */
final class CustomReportsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../../config')
        );

        //only register custom reports adapter, if the custom reports bundle is installed
        if ($container->hasDefinition('opendxp.custom_report.adapter.factories')) {
            $loader->load('custom_reports.yaml');

            $serviceLocator = $container->getDefinition('opendxp_newsletter.address_source_adapter.factories');
            $arguments = $serviceLocator->getArgument(0);
            $arguments['reportAdapter'] = new Reference('opendxp_newsletter.document.newsletter.factory.report');
            $serviceLocator->setArgument(0, $arguments);
        }
    }
}
