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

namespace OpenDxp\Bundle\NewsletterBundle\CustomReports\Tool\Adapter;

use OpenDxp\Bundle\CustomReportsBundle\Tool\Adapter\CustomReportAdapterFactoryInterface;
use OpenDxp\Bundle\CustomReportsBundle\Tool\Config;
use OpenDxp\Bundle\NewsletterBundle\Document\Newsletter\AddressSourceAdapterFactoryInterface;
use OpenDxp\Bundle\NewsletterBundle\Document\Newsletter\AddressSourceAdapterInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;

/**
 * @internal
 */
final readonly class ReportAddressSourceAdapterFactory implements AddressSourceAdapterFactoryInterface
{
    public function __construct(private readonly ServiceLocator $reportAdapterServiceLocator)
    {
    }

    public function create(array $params): ReportAdapter|AddressSourceAdapterInterface
    {
        $config = Config::getByName($params['reportId']);
        $configuration = $config->getDataSourceConfig();

        $reportAdapterType = $configuration->type;

        if (!$this->reportAdapterServiceLocator->has($reportAdapterType)) {
            throw new \RuntimeException(sprintf('Could not find Custom Report Adapter with type %s', $reportAdapterType));
        }

        /** @var CustomReportAdapterFactoryInterface $adapterFactory */
        $adapterFactory = $this->reportAdapterServiceLocator->get($reportAdapterType);
        $adapter = $adapterFactory->create($configuration, $config);

        return new ReportAdapter($params['emailFieldName'], $adapter);
    }
}
