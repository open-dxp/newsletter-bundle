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

namespace OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\SendTestNewsletter;

use OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\AddressAdapterPayload;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
final readonly class SendTestNewsletterPayload extends AddressAdapterPayload
{
    public function __construct(
        string $addressAdapterName,
        ?array $adapterParams,
        public int $id,
        public string $testMailAddress,
    ) {
        parent::__construct($addressAdapterName, $adapterParams);
    }

    public static function fromRequest(Request $request): static
    {
        return new self(
            addressAdapterName: $request->request->getString('addressAdapterName'),
            adapterParams: self::decodeAdapterParams($request),
            id: $request->request->getInt('id'),
            testMailAddress: $request->request->getString('testMailAddress'),
        );
    }
}
