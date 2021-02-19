<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use Carbon\Carbon;
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
        $this->info('Start generation...');

        Certificate::whereDoesntHave('certificable')
            ->whereDoesntHave('sessions')
            ->whereDate('created_at', '<', Carbon::now()->subDays(30))
            ->each(function (Certificate $certificate) {
                $certificate->delete();
            });

        $pemPath = Storage::path('certificates/certificates.pem');
        $tmpPath = "{$pemPath}.tmp";

        File::delete([$pemPath, $tmpPath]);

        Certificate::pluck('ca_crt_path', 'ca_md5')->each(function (
            $caPath
        ) use ($tmpPath) {
            if (Storage::exists($caPath)) {
                $this->appendToFile($tmpPath, Storage::get($caPath));
            }
        });

        $this->appendToFile($tmpPath, File::get(env('ROOT_CA_PATH')));

        File::move($tmpPath, $pemPath);

        $this->info('Generated!');
    }

    protected function appendToFile($filePath, $content)
    {
        File::append($filePath, trim($content, PHP_EOL) . PHP_EOL);
    }
}
