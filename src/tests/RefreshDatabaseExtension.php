<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use PHPUnit\Runner\AfterLastTestHook;
use PHPUnit\Runner\BeforeFirstTestHook;

class RefreshDatabaseExtension implements BeforeFirstTestHook, AfterLastTestHook
{
    use CreatesApplication;

    /**
     * @return void
     */
    public function executeBeforeFirstTest(): void
    {
        $app = $this->createApplication();
        $app->make(Kernel::class)->call('migrate:fresh');
//        $app->make(Kernel::class)->call('db:seed --class=TestDatabaseSeeder');
    }

    /**
     * @return void
     */
    public function executeAfterLastTest(): void
    {
        $app = $this->createApplication();
//        $app->make(Kernel::class)->call('migrate:rollback');
    }
}
