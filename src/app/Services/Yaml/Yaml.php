<?php declare(strict_types=1);

namespace App\Services\Yaml;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Yaml\Parser;

class Yaml
{
    /**
     * @var Parser
     */
    protected $parser;

    /**
     * Yaml constructor.
     */
    public function __construct()
    {
        $this->parser = new Parser();
    }

    /**
     * @param Importable $importable
     * @param string $input
     * @param int $flags
     * @return \Illuminate\Support\Collection
     * @throws \Throwable
     */
    public function import(Importable $importable, string $input, int $flags = 0)
    {
        $rows = $this->parse($input, $flags);

        return $this->doImport($importable, $rows);
    }

    /**
     * @param Importable $importable
     * @param string $filename
     * @param int $flags
     * @return \Illuminate\Support\Collection
     * @throws \Throwable
     */
    public function importFile(Importable $importable, string $filename, int $flags = 0)
    {
        $rows = $this->parseFile($filename, $flags);

        return $this->doImport($importable, $rows);
    }

    /**
     * @param Importable $resource
     * @param array $rows
     * @return \Illuminate\Support\Collection
     * @throws \Throwable
     */
    public function doImport(Importable $resource, array $rows)
    {
        $instances = collect();

        foreach ($rows as $row) {
            $model = $resource->toModel($row);
            $instances->push($model);
            $instances->each(function (Model $model) use ($resource, $row) {
                $resource->beforeImport($row, $model);
            });
            $model->saveOrFail();
            $instances->each(function (Model $model) use ($resource, $row) {
                $resource->afterImport($row, $model);
            });
        }

        return $instances;
    }

    /**
     * @param string $input
     * @param int $flags
     * @return mixed
     */
    public function parse(string $input, int $flags = 0)
    {
        return $this->parser->parse($input, $flags);
    }

    /**
     * @param string $filename
     * @param int $flags
     * @return mixed
     */
    public function parseFile(string $filename, int $flags = 0)
    {
        return $this->parser->parseFile($filename, $flags);
    }
}
