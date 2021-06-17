<?php

namespace App\Observers;

use App\Models\SimulatorPlugin;
use Storage;

class SimulatorPluginObserver
{
    public function updated(SimulatorPlugin $plugin): void
    {
        if ($plugin->wasChanged('file_path')) {
            Storage::delete($plugin->getOriginal('file_path'));
        }
    }

    public function deleted(SimulatorPlugin $plugin): void
    {
        Storage::delete($plugin->file_path);
    }
}
