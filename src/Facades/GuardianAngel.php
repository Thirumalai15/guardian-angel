<?php

namespace Icrewsystems\GuardianAngel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Icrewsystems\GuardianAngel\GuardianAngel
 */
class GuardianAngel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Icrewsystems\GuardianAngel\GuardianAngel::class;
    }
}
