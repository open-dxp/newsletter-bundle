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

namespace OpenDxp\Bundle\NewsletterBundle\Event;

class DocumentEvents
{
    /**
     * Arguments:
     *  - mail | \OpenDxp\Mail | the opendxp mail instance
     *  - document | \OpenDxp\Model\Document\Newsletter | the newsletter document
     *  - sendingContainer | \OpenDxp\Document\Newsletter | sending param container of newsletter helper
     *  - mailer | \OpenDxp\Mail\Mailer|null | newsletter specific mailer if enabled in system settings
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    public const NEWSLETTER_PRE_SEND = 'opendxp.document.newsletter.pre_send';

    /**
     * Arguments:
     *  - mail | \OpenDxp\Mail | the opendxp mail instance
     *  - document | \OpenDxp\Model\Document\Newsletter | the newsletter document
     *  - sendingContainer | \OpenDxp\Document\Newsletter | sending param container of newsletter helper
     *  - mailer | \OpenDxp\Mail\Mailer|null | newsletter specific swift mailer if enabled in system settings
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    public const NEWSLETTER_POST_SEND = 'opendxp.document.newsletter.post_send';
}
