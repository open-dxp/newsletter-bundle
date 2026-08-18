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

namespace OpenDxp\Bundle\NewsletterBundle\Controller\Document;

use OpenDxp\Bundle\AdminBundle\Attribute\SessionIdentityAware;
use OpenDxp\Bundle\AdminBundle\Controller\Admin\Document\DocumentControllerBase;
use OpenDxp\Bundle\AdminBundle\Handler\Document\Email\GetEmailData\GetEmailDataHandler;
use OpenDxp\Bundle\AdminBundle\Payload\Common\IdBodyPayload;
use OpenDxp\Bundle\AdminBundle\Payload\Common\IdQueryPayload;
use OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\CalculateRecipients\CalculateRecipientsHandler;
use OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\CalculateRecipients\CalculateRecipientsPayload;
use OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\CheckRecipientSql\CheckRecipientSqlHandler;
use OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\CheckRecipientSql\CheckRecipientSqlPayload;
use OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\GetAvailableClasses\GetAvailableClassesHandler;
use OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\GetAvailableReports\GetAvailableReportsHandler;
use OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\GetAvailableReports\GetAvailableReportsPayload;
use OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\GetSendStatus\GetSendStatusHandler;
use OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\SaveNewsletter\SaveNewsletterHandler;
use OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\SaveNewsletter\SaveNewsletterPayload;
use OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\SendNewsletter\SendNewsletterHandler;
use OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\SendNewsletter\SendNewsletterPayload;
use OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\SendTestNewsletter\SendTestNewsletterHandler;
use OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\SendTestNewsletter\SendTestNewsletterPayload;
use OpenDxp\Bundle\NewsletterBundle\Handler\Document\Newsletter\StopSend\StopSendHandler;
use OpenDxp\Bundle\NewsletterBundle\Security\NewsletterPermission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * @internal
 */
#[IsGranted(NewsletterPermission::Newsletters->value)]
#[Route('/newsletter', name: 'opendxp_bundle_newsletter_document_newsletter_')]
class NewsletterController extends DocumentControllerBase
{
    #[Route('/get-data-by-id', name: 'getdatabyid', methods: ['GET'])]
    #[SessionIdentityAware]
    public function getDataByIdAction(
        GetEmailDataHandler $handler,
        IdQueryPayload $payload,
    ): JsonResponse {
        return $this->apiJson($handler($payload), rootProperty: 'data');
    }

    #[Route('/save', name: 'save', methods: ['PUT', 'POST'])]
    #[SessionIdentityAware]
    public function saveAction(SaveNewsletterPayload $payload, SaveNewsletterHandler $handler): JsonResponse
    {
        return $this->apiJson($handler($payload));
    }

    #[Route('/checksql', name: 'checksql', methods: ['POST'])]
    public function checksqlAction(CheckRecipientSqlPayload $payload, CheckRecipientSqlHandler $handler): JsonResponse
    {
        return $this->apiJson($handler($payload));
    }

    #[Route('/get-available-classes', name: 'getavailableclasses', methods: ['GET'])]
    public function getAvailableClassesAction(GetAvailableClassesHandler $handler): JsonResponse
    {
        return $this->apiJson($handler(), envelope: false);
    }

    #[Route('/get-available-reports', name: 'getavailablereports', methods: ['GET'])]
    public function getAvailableReportsAction(
        GetAvailableReportsPayload $payload,
        GetAvailableReportsHandler $handler,
    ): JsonResponse {
        return $this->apiJson($handler($payload), envelope: false);
    }

    #[Route('/get-send-status', name: 'getsendstatus', methods: ['GET'])]
    public function getSendStatusAction(IdQueryPayload $payload, GetSendStatusHandler $handler): JsonResponse
    {
        return $this->apiJson($handler($payload));
    }

    #[Route('/stop-send', name: 'stopsend', methods: ['POST'])]
    public function stopSendAction(IdBodyPayload $payload, StopSendHandler $handler): JsonResponse
    {
        $handler($payload);

        return $this->apiOk();
    }

    #[Route('/send', name: 'send', methods: ['POST'])]
    public function sendAction(SendNewsletterPayload $payload, SendNewsletterHandler $handler): JsonResponse
    {
        $handler($payload);

        return $this->apiOk();
    }

    #[Route('/calculate', name: 'calculate', methods: ['POST'])]
    public function calculateAction(CalculateRecipientsPayload $payload, CalculateRecipientsHandler $handler): JsonResponse
    {
        return $this->apiJson($handler($payload));
    }

    #[Route('/send-test', name: 'sendtest', methods: ['POST'])]
    public function sendTestAction(SendTestNewsletterPayload $payload, SendTestNewsletterHandler $handler): JsonResponse
    {
        return $this->apiJson($handler($payload));
    }
}
