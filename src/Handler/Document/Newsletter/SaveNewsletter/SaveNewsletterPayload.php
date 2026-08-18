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

use OpenDxp\Bundle\AdminBundle\Handler\Document\Page\PagePayload;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
final readonly class SaveNewsletterPayload extends PagePayload
{
    public function __construct(
        int $id,
        string $task,
        ?array $settings,
        ?array $editables,
        bool $appendEditables,
        ?array $properties,
        ?array $scheduler,
        ?bool $missingRequiredEditable,
        public ?array $plaintext = null,
    ) {
        parent::__construct(
            $id,
            $task,
            $settings,
            $editables,
            $appendEditables,
            $properties,
            $scheduler,
            $missingRequiredEditable,
        );
    }

    public static function fromRequest(Request $request): static
    {
        $base = PagePayload::fromRequest($request);
        $plaintext = json_decode($request->request->getString('plaintext'), true);

        return new static(
            id: $base->id,
            task: $base->task,
            settings: $base->settings,
            editables: $base->editables,
            appendEditables: $base->appendEditables,
            properties: $base->properties,
            scheduler: $base->scheduler,
            missingRequiredEditable: $base->missingRequiredEditable,
            plaintext: is_array($plaintext) ? $plaintext : null,
        );
    }
}
