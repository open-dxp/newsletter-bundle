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

namespace OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\StopSend;

use OpenDxp\Bundle\AdminBundle\Payload\Common\IdBodyPayload;
use OpenDxp\Bundle\NewsletterBundle\Model\Document\Newsletter;
use OpenDxp\Model\Tool\TmpStore;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @internal
 */
final class StopSendHandler
{
    public function __invoke(IdBodyPayload $payload): void
    {
        $newsletter = Newsletter::getById($payload->id);
        if (!$newsletter) {
            throw new NotFoundHttpException('Newsletter not found');
        }

        TmpStore::delete($newsletter->getTmpStoreId());
    }
}
