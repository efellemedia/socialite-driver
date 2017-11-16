<?php

namespace Efelle\SocialiteDriver;

use SocialiteProviders\Manager\SocialiteWasCalled;

class EfelleExtendSocialite
{
    /**
     * Execute the provider.
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('efelle', __NAMESPACE__.'\Provider');
    }
}
