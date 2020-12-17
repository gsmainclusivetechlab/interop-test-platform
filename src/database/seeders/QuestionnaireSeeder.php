<?php

namespace Database\Seeders;

use App\Imports\QuestionnaireImport;
use Illuminate\Database\Seeder;
use Symfony\Component\Yaml\Yaml;

class QuestionnaireSeeder extends Seeder
{
    /**
     * @return void
     * @throws Throwable
     */
    public function run()
    {
        $path = database_path('seeders/questionnaire/questions.yml');

        (new QuestionnaireImport())->import(
            Yaml::parse(file_get_contents($path))
        );
    }
}
