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

namespace OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\CheckRecipientSql;

use Exception;
use OpenDxp\Model\DataObject\Listing;

/**
 * @internal
 */
final class CheckRecipientSqlHandler
{
    public function __invoke(CheckRecipientSqlPayload $payload): CheckRecipientSqlResult
    {
        $listingClass = '\\OpenDxp\\Model\\DataObject\\' . ucfirst($payload->class) . '\\Listing';

        // an unknown class raises ClassNotFoundError, which is an Error and would escape the catch below
        if ($payload->class === '' || !class_exists($listingClass)) {
            return new CheckRecipientSqlResult(count: 0, success: false);
        }

        try {
            /** @var Listing $list */
            $list = new $listingClass();

            $conditions = ['(newsletterActive = 1 AND newsletterConfirmed = 1)'];
            if ($payload->objectFilterSQL !== '') {
                $conditions[] = $payload->objectFilterSQL;
            }
            $list->setCondition(implode(' AND ', $conditions));

            // getDao() avoids the error log entries AbstractModel::__call() would produce
            return new CheckRecipientSqlResult(count: $list->getDao()->getTotalCount(), success: true);
        } catch (Exception) {
            return new CheckRecipientSqlResult(count: 0, success: false);
        }
    }
}
