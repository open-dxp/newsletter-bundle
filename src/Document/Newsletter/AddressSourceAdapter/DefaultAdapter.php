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

namespace OpenDxp\Bundle\NewsletterBundle\Document\Newsletter\AddressSourceAdapter;

use OpenDxp\Bundle\NewsletterBundle\Document\Newsletter\AddressSourceAdapterInterface;
use OpenDxp\Bundle\NewsletterBundle\Document\Newsletter\SendingParamContainer;
use OpenDxp\Db;
use OpenDxp\Model\DataObject\ClassDefinition;
use OpenDxp\Model\DataObject\Listing;

/**
 * @internal
 */
class DefaultAdapter implements AddressSourceAdapterInterface
{
    protected ?string $class = null;

    protected ?string $condition = null;

    protected int $elementsTotal = 0;

    protected ?Listing $list = null;

    public function __construct(array $params)
    {
        $this->class = $params['class'];
        $this->condition = empty($params['condition']) ? $params['objectFilterSQL'] : $params['condition'];
    }

    protected function getListing(): ?Listing
    {
        if (null === $this->list) {
            $objectList = '\\OpenDxp\\Model\\DataObject\\' . ucfirst((string) $this->class) . '\\Listing';
            $this->list = new $objectList();

            $conditions = ['(newsletterActive = 1 AND newsletterConfirmed = 1)'];
            if ($this->condition) {
                $conditions[] = '(' . $this->condition . ')';
            }

            $this->list->setCondition(implode(' AND ', $conditions));
            $this->list->setOrderKey('email');
            $this->list->setOrder('ASC');

            $this->elementsTotal = $this->list->getTotalCount();
        }

        return $this->list;
    }

    public function getMailAddressesForBatchSending(): array
    {
        if (!$this->class) {
            return [];
        }
        $listing = $this->getListing();
        $ids = $listing->loadIdList();

        $class = ClassDefinition::getByName($this->class);
        $tableName = 'object_' . $class->getId();

        $emails = [];

        if (count($ids) > 0) {
            $db = Db::get();
            $emails = $db->fetchFirstColumn("SELECT email FROM $tableName WHERE id IN (" . implode(',', $ids) . ')');
        }

        $containers = [];
        foreach ($emails as $email) {
            $containers[] = new SendingParamContainer($email, ['emailAddress' => $email]);
        }

        return $containers;
    }

    public function getParamsForTestSending(string $emailAddress): SendingParamContainer
    {
        if ($this->class) {
            $listing = $this->getListing();
            $listing->setOrderKey('RAND()', false);
            $listing->setLimit(1);
            $listing->setOffset(0);

            $object = $listing->current();

            if ($object) {
                return new SendingParamContainer($emailAddress, [
                    'gender' => method_exists($object, 'getGender') ? $object->getGender() : '',
                    'firstname' => method_exists($object, 'getFirstname') ? $object->getFirstname() : '',
                    'lastname' => method_exists($object, 'getLastname') ? $object->getLastname() : '',
                    'email' => $emailAddress,
                    'token' => 'token',
                    'object' => $object,
                ]);
            }
        }

        return new SendingParamContainer($emailAddress, [
            'gender' => '',
            'firstname' => '',
            'lastname' => '',
            'email' => $emailAddress,
            'token' => 'token',
            'object' => null,
        ]);
    }

    public function getTotalRecordCount(): int
    {
        if ($this->class) {
            $this->getListing();
        }

        return $this->elementsTotal;
    }

    public function getParamsForSingleSending(int $limit, int $offset): array
    {
        if (!$this->class) {
            return [];
        }
        $listing = $this->getListing();
        $listing->setLimit($limit);
        $listing->setOffset($offset);
        $objects = $listing->load();

        $containers = [];

        foreach ($objects as $object) {
            if (method_exists($object, 'getEmail') && $object->getEmail()) {
                $containers[] = new SendingParamContainer($object->getEmail(), [
                    'gender' => method_exists($object, 'getGender') ? $object->getGender() : '',
                    'firstname' => method_exists($object, 'getFirstname') ? $object->getFirstname() : '',
                    'lastname' => method_exists($object, 'getLastname') ? $object->getLastname() : '',
                    'email' => $object->getEmail(),
                    'token' => $object->getProperty('token'),
                    'object' => $object,
                ]);
            }
        }

        return $containers;
    }
}
