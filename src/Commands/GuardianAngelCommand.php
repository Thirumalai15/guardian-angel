<?php

namespace Icrewsystems\GuardianAngel\Commands;

use Exception;
use Icrewsystems\GuardianAngel\Services\GuardianAngelService;
use Illuminate\Console\Command;

class GuardianAngelCommand extends Command
{
    public $signature = 'guardian-angel';

    public $description = 'A test command to check whether everything is working as expected';

    public function handle(): int
    {
        try {
            $throwException = true;

            if (config('app.env') != 'production') {
                $this->comment('❌ App environment is not set to production. Set it to production');
                $throwException = false;
            } else {
                $this->comment('✔ App environment is set to production');
            }

            if (config('queue.default') != 'database') {
                $this->comment('❌ Queue connection is not set to database. Set it to database');
                $throwException = false;
            } else {
                $this->comment('✔ Queue connection is set to database');
            }

            if (! config('app.project_key')) {
                $this->comment('❌ Project key is not set');
                $throwException = false;
            } else {
                $this->comment('✔ Project key is set');
            }

            if (! config('app.exception_url')) {
                $this->comment('❌ Exception URL is not set');
                $throwException = false;
            } else {
                $this->comment('✔ Exception URL is set');
            }

            if ($throwException) {
                $this->comment('✔ Everything is set perfectly. Throwing a test exception via console');
                $this->comment('Throwing the exception.....');

                throw new Exception('Hello, this is a test exception thrown from '.config('app.name').' via console');
            } else {
                $this->comment('❌ Some conditions are not met. The exception will not be thrown.');
            }

        } catch (\Exception $e) {
            $this->error($e->getMessage());

            $guardianAngelService = app(GuardianAngelService::class);
            $guardianAngelService->logException($e);
        }

        return self::SUCCESS;
    }
}
