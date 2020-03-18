<?php

namespace App\Services\Yaml\Concerns;

use App\Services\Yaml\Yaml;

trait InteractsWithImport
{
    /**
     * @param string $input
     * @param int $flags
     * @return \Illuminate\Support\Collection
     */
    public function import(string $input, int $flags = 0)
    {
        return $this->getImporter()->import($this, $input, $flags);
    }

    /**
     * @param string $filename
     * @param int $flags
     * @return \Illuminate\Support\Collection
     */
    public function importFile(string $filename, int $flags = 0)
    {
        return $this->getImporter()->importFile($this, $filename, $flags);
    }

    /**
     * @return Yaml
     */
    protected function getImporter(): Yaml
    {
        return new Yaml();
    }
}
