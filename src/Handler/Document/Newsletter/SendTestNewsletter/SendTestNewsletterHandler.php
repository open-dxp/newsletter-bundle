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

use Exception;
use OpenDxp\Bundle\NewsletterBundle\Document\Newsletter\AddressSourceAdapterFactoryInterface;
use OpenDxp\Bundle\NewsletterBundle\Model\Document\Newsletter;
use OpenDxp\Bundle\NewsletterBundle\Tool\Newsletter as NewsletterTool;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @internal
 */
final class SendTestNewsletterHandler
{
    public function __construct(private readonly ContainerInterface $addressSourceAdapterFactories)
    {
    }

    public function __invoke(SendTestNewsletterPayload $payload): SendTestNewsletterResult
    {
        $newsletter = Newsletter::getById($payload->id);
        if (!$newsletter) {
            throw new NotFoundHttpException('Newsletter not found');
        }

        if ($payload->testMailAddress === '') {
            return SendTestNewsletterResult::failed('Please provide a valid email address to send test newsletter');
        }

        if (!$this->addressSourceAdapterFactories->has($payload->addressAdapterName)) {
            return SendTestNewsletterResult::failed(sprintf(
                'Cannot send newsletters because Address Source Adapter with identifier %s could not be found',
                $payload->addressAdapterName
            ));
        }

        /** @var AddressSourceAdapterFactoryInterface $factory */
        $factory = $this->addressSourceAdapterFactories->get($payload->addressAdapterName);
        $adapter = $factory->create($payload->adapterParams ?? []);

        $sendingContainer = $adapter->getParamsForTestSending($payload->testMailAddress);

        try {
            $mail = NewsletterTool::prepareMail($newsletter, $sendingContainer);
            NewsletterTool::sendNewsletterDocumentBasedMail($mail, $sendingContainer);
        } catch (Exception $e) {
            return SendTestNewsletterResult::failed($e->getMessage());
        }

        return SendTestNewsletterResult::sent();
    }
}
