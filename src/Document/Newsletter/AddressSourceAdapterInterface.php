<?php
declare(strict_types=1);

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PCL
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
