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

namespace OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\GetAvailableClasses;

use OpenDxp\Bundle\NewsletterBundle\Model\DataObject\ClassDefinition\Data\NewsletterActive;
use OpenDxp\Bundle\NewsletterBundle\Model\DataObject\ClassDefinition\Data\NewsletterConfirmed;
use OpenDxp\Model\DataObject\ClassDefinition\Data\Email;
use OpenDxp\Model\DataObject\ClassDefinition\Listing;

/**
 * @internal
 */
final class GetAvailableClassesHandler
{
    public function __invoke(): GetAvailableClassesResult
    {
        $availableClasses = [];

        foreach ((new Listing())->load() as $class) {
            $fieldCount = 0;
            foreach ($class->getFieldDefinitions() as $fd) {
                if ($fd instanceof NewsletterActive
                    || $fd instanceof NewsletterConfirmed
                    || $fd instanceof Email) {
                    $fieldCount++;
                }
            }

            if ($fieldCount >= 3) {
                $availableClasses[] = ['name' => $class->getName()];
            }
        }

        return new GetAvailableClassesResult(data: $availableClasses);
    }
}
