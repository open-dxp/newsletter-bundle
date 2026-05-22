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
 * @copyright  Modification Copyright (c) OpenDXP (https://www.opendxp.io)
 * @license    https://www.gnu.org/licenses/gpl-3.0.html  GNU General Public License version 3 (GPLv3)
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
use Override;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use function dirname;

class OpenDxpNewsletterBundle extends AbstractOpenDxpBundle implements OpenDxpBundleAdminClassicInterface
{
    use BundleAdminClassicTrait;
    use PackageVersionTrait;

    #[Override]
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

    #[Override]
    public function getPath(): string
    {
        return dirname(__DIR__);
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
