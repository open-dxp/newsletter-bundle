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

use OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\AddressAdapterPayload;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
final readonly class CalculateRecipientsPayload extends AddressAdapterPayload
{
    public static function fromRequest(Request $request): static
    {
        return new self(
            addressAdapterName: $request->request->getString('addressAdapterName'),
            adapterParams: self::decodeAdapterParams($request),
        );
    }
}
