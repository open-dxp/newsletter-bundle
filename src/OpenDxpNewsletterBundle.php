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

namespace OpenDxp\Bundle\NewsletterBundle;

use OpenDxp\Bundle\AdminBundle\OpenDxpAdminBundle;
use OpenDxp\Bundle\NewsletterBundle\DependencyInjection\Compiler\CustomReportsPass;
use OpenDxp\Bundle\NewsletterBundle\DependencyInjection\OpenDxpNewsletterExtension;
use OpenDxp\Extension\Bundle\AbstractOpenDxpBundle;
use OpenDxp\Extension\Bundle\OpenDxpBundleAdminClassicInterface;
use OpenDxp\Extension\Bundle\Traits\BundleAdminClassicTrait;
use OpenDxp\Extension\Bundle\Traits\PackageVersionTrait;
use OpenDxp\HttpKernel\BundleCollection\BundleCollection;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class OpenDxpNewsletterBundle extends AbstractOpenDxpBundle implements OpenDxpBundleAdminClassicInterface
{
    use BundleAdminClassicTrait;
    use PackageVersionTrait;

    public function getContainerExtension(): ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new OpenDxpNewsletterExtension();
        }

        return $this->extension;
    }

    public function getJsPaths(): array
    {
        return [
            '/bundles/opendxpnewsletter/js/startup.js',
            '/bundles/opendxpnewsletter/js/document/newsletter.js',
            '/bundles/opendxpnewsletter/js/document/newsletters/settings.js',
            '/bundles/opendxpnewsletter/js/document/newsletters/sendingPanel.js',
            '/bundles/opendxpnewsletter/js/document/newsletters/plaintextPanel.js',
            '/bundles/opendxpnewsletter/js/document/newsletters/addressSourceAdapters/default.js',
            '/bundles/opendxpnewsletter/js/document/newsletters/addressSourceAdapters/csvList.js',
            '/bundles/opendxpnewsletter/js/document/newsletters/addressSourceAdapters/report.js',
            '/bundles/opendxpnewsletter/js/object/classes/data/newsletterActive.js',
            '/bundles/opendxpnewsletter/js/object/classes/data/newsletterConfirmed.js',
            '/bundles/opendxpnewsletter/js/object/tags/newsletterActive.js',
            '/bundles/opendxpnewsletter/js/object/tags/newsletterConfirmed.js',
        ];
    }

    public function getCssPaths(): array
    {
        return [
            '/bundles/opendxpnewsletter/css/icons.css',
        ];
    }

    public function getEditmodeJsPaths(): array
    {
        return [];
    }

    public function getEditmodeCssPaths(): array
    {
        return [];
    }

    public function getInstaller(): Installer
    {
        return $this->container->get(Installer::class);
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new CustomReportsPass());
    }

    public static function registerDependentBundles(BundleCollection $collection): void
    {
        $collection->addBundle(new OpenDxpAdminBundle(), 60);
    }
}
