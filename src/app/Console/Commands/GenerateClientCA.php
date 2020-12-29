<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use File;
use Illuminate\Console\Command;
use Storage;

class GenerateClientCA extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'certificates:generate-ca';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate client CA file';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        Certificate::whereDoesntHave('group')
            ->whereDoesntHave('sessions')
            ->each(function (Certificate $certificate) {
                $certificate->delete();
            });

        $pemPath = Storage::path('certificates/certificates.pem');
        $tmpPath = "{$pemPath}.tmp";

        File::delete([$pemPath, $tmpPath]);

        Certificate::pluck('ca_crt_path', 'ca_md5')->each(function (
            $caPath
        ) use ($tmpPath) {
            File::append(
                $tmpPath,
                trim(Storage::get($caPath), PHP_EOL) . PHP_EOL
            );
        });

        File::move($tmpPath, $pemPath);
    }
}
