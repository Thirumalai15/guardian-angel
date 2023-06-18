<?php

namespace Icrewsystems\GuardianAngel;

use Icrewsystems\GuardianAngel\Services\GuardianAngelService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Icrewsystems\GuardianAngel\Commands\GuardianAngelCommand;

class GuardianAngelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('guardian-angel')
            ->hasConfigFile()
            ->hasCommand(GuardianAngelCommand::class);
    }



}
