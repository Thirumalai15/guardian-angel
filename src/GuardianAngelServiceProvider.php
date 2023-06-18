<?php

namespace Icrewsystems\GuardianAngel;

use Icrewsystems\GuardianAngel\Commands\GuardianAngelCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
