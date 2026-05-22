<?php

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

namespace OpenDxp\Bundle\NewsletterBundle\Model\Document\Newsletter;

use Exception;
use OpenDxp\Bundle\NewsletterBundle\Model\Document\Newsletter;
use OpenDxp\Model;
use Override;

/**
 * @internal
 *
 * @property Newsletter $model
 */
class Dao extends Model\Document\Email\Dao
{
    /**
     * Get the data for the object by the given id, or by the id which is set in the object
     *
     *
     * @throws Model\Exception\NotFoundException
     */
    #[Override]
    public function getById(?int $id = null): void
    {
        if ($id != null) {
            $this->model->setId($id);
        }

        $data = $this->db->fetchAssociative("SELECT documents.*, documents_newsletter.*, tree_locks.locked FROM documents
            LEFT JOIN documents_newsletter ON documents.id = documents_newsletter.id
            LEFT JOIN tree_locks ON documents.id = tree_locks.id AND tree_locks.type = 'document'
                WHERE documents.id = ?", [$this->model->getId()]);

        if (!empty($data['id'])) {
            $this->assignVariablesToModel($data);
        } else {
            throw new Model\Exception\NotFoundException('Newsletter Document with the ID ' . $this->model->getId() . " doesn't exists");
        }
    }

    #[Override]
    public function create(): void
    {
        parent::create();

        $this->db->insert('documents_newsletter', [
            'id' => $this->model->getId(),
        ]);
    }

    /**
     * Deletes the object (and data) from database
     *
     * @throws Exception
     */
    #[Override]
    public function delete(): void
    {
        $this->deleteAllProperties();

        parent::delete();
    }
}
