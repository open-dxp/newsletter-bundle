<?php

declare(strict_types=1);

namespace OpenDxp\Bundle\NewsletterBundle\Tests\Application;

use OpenDxp\Bundle\NewsletterBundle\OpenDxpNewsletterBundle;
use OpenDxp\HttpKernel\BundleCollection\BundleCollection;
use OpenDxp\TestFoundation\Kernel\TestKernel as Foundation;

final class TestKernel extends Foundation
{
    public function registerBundlesToCollection(BundleCollection $collection): void
    {
        $collection->addBundle(new OpenDxpNewsletterBundle());
    }
}
