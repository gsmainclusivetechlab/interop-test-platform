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
        $this->createApplication()->make(Kernel::class)->call('migrate:fresh');
    }

    /**
     * @return void
     */
    public function executeAfterLastTest(): void
    {
        $this->createApplication()->make(Kernel::class)->call('migrate:rollback');
    }
}
