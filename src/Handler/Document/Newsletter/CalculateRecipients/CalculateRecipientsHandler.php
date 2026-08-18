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

namespace OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\CalculateRecipients;

use OpenDxp\Bundle\NewsletterBundle\Document\Newsletter\AddressSourceAdapterFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 * @internal
 */
final class CalculateRecipientsHandler
{
    public function __construct(private readonly ContainerInterface $addressSourceAdapterFactories)
    {
    }

    public function __invoke(CalculateRecipientsPayload $payload): CalculateRecipientsResult
    {
        if (!$this->addressSourceAdapterFactories->has($payload->addressAdapterName)) {
            return CalculateRecipientsResult::failed(sprintf(
                'Cannot send newsletters because Address Source Adapter with identifier %s could not be found',
                $payload->addressAdapterName
            ));
        }

        /** @var AddressSourceAdapterFactoryInterface $factory */
        $factory = $this->addressSourceAdapterFactories->get($payload->addressAdapterName);
        $adapter = $factory->create($payload->adapterParams ?? []);

        return CalculateRecipientsResult::counted($adapter->getTotalRecordCount());
    }
}
