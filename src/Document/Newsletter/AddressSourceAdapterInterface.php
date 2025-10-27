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
 * @copyright  Modification Copyright (c) OpenDXP (https://www.opendxp.ch)
 * @license    https://www.gnu.org/licenses/gpl-3.0.html  GNU General Public License version 3 (GPLv3)
 */

namespace OpenDxp\Bundle\NewsletterBundle\Document\Newsletter;

interface AddressSourceAdapterInterface
{
    /**
     * returns array of email addresses for batch sending
     *
     * @return SendingParamContainer[]
     */
    public function getMailAddressesForBatchSending(): array;

    /**
     * returns params to be set on mail for test sending
     *
     *
     */
    public function getParamsForTestSending(string $emailAddress): SendingParamContainer;

    /**
     * returns total number of newsletter recipients
     *
     */
    public function getTotalRecordCount(): int;

    /**
     * returns array of params to be set on mail for single sending
     *
     *
     * @return SendingParamContainer[]
     */
    public function getParamsForSingleSending(int $limit, int $offset): array;
}
