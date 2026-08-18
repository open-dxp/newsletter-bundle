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

namespace OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\SendNewsletter;

use OpenDxp\Bundle\NewsletterBundle\Messenger\SendNewsletterMessage;
use OpenDxp\Bundle\NewsletterBundle\Model\Document\Newsletter;
use OpenDxp\Model\Tool\TmpStore;
use OpenDxp\Tool;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @internal
 */
final class SendNewsletterHandler
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    public function __invoke(SendNewsletterPayload $payload): void
    {
        $newsletter = Newsletter::getById($payload->id);
        if (!$newsletter) {
            throw new NotFoundHttpException('Newsletter not found');
        }

        $tmpStoreId = $newsletter->getTmpStoreId();

        if (TmpStore::get($tmpStoreId)) {
            throw new RuntimeException('Newsletter sending already in progress, need to finish first.');
        }

        TmpStore::add($tmpStoreId, [
            'documentId' => $newsletter->getId(),
            'addressSourceAdapterName' => $payload->addressAdapterName,
            'adapterParams' => $payload->adapterParams,
            'inProgress' => false,
            'progress' => 0,
        ], 'newsletter');

        $this->messageBus->dispatch(new SendNewsletterMessage($tmpStoreId, Tool::getHostUrl()));
    }
}
