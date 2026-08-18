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

use OpenDxp\Bundle\AdminBundle\Handler\ConditionalResultInterface;

/**
 * @internal
 */
final readonly class CalculateRecipientsResult implements ConditionalResultInterface
{
    private function __construct(
        public int $count,
        public ?string $message,
        private bool $success,
    ) {
    }

    public static function counted(int $count): self
    {
        return new self($count, null, true);
    }

    public static function failed(string $message): self
    {
        return new self(0, $message, false);
    }

    public function isSuccessful(): bool
    {
        return $this->success;
    }
}
