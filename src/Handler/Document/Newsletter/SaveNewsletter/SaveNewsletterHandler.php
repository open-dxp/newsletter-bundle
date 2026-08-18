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

namespace OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\SaveNewsletter;

use OpenDxp\Bundle\AdminBundle\Coordinator\Document\DocumentPersistenceCoordinator;
use OpenDxp\Bundle\AdminBundle\Mapper\Document\DocumentPayloadMapper;
use OpenDxp\Bundle\AdminBundle\Service\Element\ElementDraftService;
use OpenDxp\Bundle\NewsletterBundle\Model\Document\Newsletter;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @internal
 */
final class SaveNewsletterHandler
{
    public function __construct(
        private readonly ElementDraftService $elementDraftService,
        private readonly DocumentPayloadMapper $mapper,
        private readonly DocumentPersistenceCoordinator $coordinator,
    ) {
    }

    public function __invoke(SaveNewsletterPayload $payload): SaveNewsletterPublishedResult|SaveNewsletterDraftResult
    {
        $loadedNewsletter = Newsletter::getById($payload->id);
        if (!$loadedNewsletter) {
            throw new NotFoundHttpException('Document not found');
        }

        $newsletter = $this->elementDraftService->resolveDraft($loadedNewsletter);

        $this->mapper->applyPagePayload($payload, $newsletter, $payload->task);

        if ($payload->plaintext !== null) {
            $newsletter->setValues($payload->plaintext);
        }

        $persistenceData = $this->coordinator->save($newsletter, $payload->task);

        $this->elementDraftService->saveDocument($newsletter);

        if ($payload->task === 'publish' || $payload->task === 'unpublish') {
            return new SaveNewsletterPublishedResult(
                data: $persistenceData->data,
                treeData: $persistenceData->treeData,
            );
        }

        return new SaveNewsletterDraftResult(draft: $persistenceData->draft ?? []);
    }
}
