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

use OpenDxp\Bundle\AdminBundle\Handler\ConditionalResultInterface;

/**
 * @internal
 */
final readonly class CheckRecipientSqlResult implements ConditionalResultInterface
{
    public function __construct(
        public int $count,
        private bool $success,
    ) {
    }

    public function isSuccessful(): bool
    {
        return $this->success;
    }
}
