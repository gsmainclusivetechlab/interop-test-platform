<?php

namespace App\Http\Exports;

use App\Http\Requests\SessionRequest;
use App\Http\Resources\UseCaseResource;
use App\Models\QuestionnaireSection;
use App\Models\Session;
use App\Models\TestRun;
use App\Models\UseCase;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\PhpWord;

class ComplianceSessionExport
{
    public function export(Session $session): PhpWord
    {
        $wordFile = new PhpWord();

        $section = $wordFile->addSection();

        $this->title($section, 'Info');
        $this->line($section, 'Session name', $session->name);
        $this->line($section, 'Session description', $session->description);
        $this->line(
            $section,
            'Group name',
            $session->owner->groups->implode('name', ', ') ?: null
        );

        foreach ($session->components as $component) {
            $this->line($section, 'SUT', $component->name);

            $textRun = $section->addTextRun();
            $textRun->addText("{$component->name} URL: ", [
                'size' => 12,
                'bold' => true,
            ]);
            $textRun->addLink(
                $session->getBaseUriOfComponent($component),
                null,
                ['size' => 12]
            );

            $section->addText(PHP_EOL);
        }

        if ($session->environments) {
            $this->title($section, 'Environments');

            foreach ($session->environments as $key => $value) {
                $textRun = $section->addTextRun();
                $textRun->addText("{$key}: ", ['size' => 12, 'bold' => true]);
                $textRun->addText($value, ['size' => 12]);
            }

            $section->addText(PHP_EOL);
        }

        $this->title($section, 'Events');
        $this->line($section, 'Created', $session->created_at);
        $this->line($section, 'Completed', $session->completed_at);
        $this->line($section, $session->status_name, $session->closed_at);

        $section->addText(PHP_EOL);

        $this->title($section, 'Questionnaire');
        foreach (
            QuestionnaireSection::getSessionQuestionnaire($session)
            as $sectionKey => $questionnaireSection
        ) {
            $this->title(
                $section,
                $sectionKey + 1 . ". {$questionnaireSection->name}",
                12
            );

            foreach ($questionnaireSection->questions as $key => $question) {
                $key += 1;
                $this->line($section, "Q{$key}", $question->question);
                $this->line(
                    $section,
                    "A{$key}",
                    implode(', ', $question->answers_names)
                );

                $section->addText(PHP_EOL);
            }
        }

        $this->title($section, 'Test runs');
        $table = $section->addTable([
            'borderSize' => 6,
            'cellMargin' => 80,
            'cellSpacing' => 50,
        ]);

        $style = ['bold' => true];
        $table->addRow();
        $table->addCell(4500)->addText(__('Test case'), $style);
        $table->addCell(1500)->addText(__('Status'), $style);
        $table->addCell(1500)->addText(__('Duration'), $style);
        $table->addCell(1500)->addText(__('Attempts'), $style);

        foreach ($session->testCases as $testCase) {
            $status = __('Incomplete');

            /** @var TestRun $lastTestRun */
            $lastTestRun = $testCase
                ->lastTestRun()
                ->where('session_id', $session->id)
                ->first();
            if ($lastTestRun && $lastTestRun->completed_at) {
                $status = __($lastTestRun->successful ? 'Pass' : 'Fail');
            }

            $table->addRow();
            $table->addCell()->addText($testCase->name);
            $table->addCell()->addText($status);
            $table
                ->addCell()
                ->addText($lastTestRun ? "{$lastTestRun->duration} ms" : null);
            $table
                ->addCell()
                ->addText(
                    (string)$testCase->testRuns
                        ->where('session_id', $session->id)
                        ->count()
                );
        }

        return $wordFile;
    }

    public function exportPdf(Session $session, array $request): PhpWord
    {
        $wordFile = new PhpWord();

        $section = $wordFile->addSection();

        $useCases = UseCase::WithTestCasesAndTestRunsOfSession($session, $request['test_runs'])->get();

        $sortUseCases = [];
        foreach ($useCases as $useCase) {
            $sort = [
                'id' => $useCase->id,
                'name' => $useCase->name,
                'testCases' => [
                    'positive' => [],
                    'negative' => [],
                ]
            ];
            foreach ($useCase->testCases as $testCase) {
                if ($testCase->behavior === 'positive') {
                    $sort['testCases']['positive'][] = $testCase;
                }
                if ($testCase->behavior === 'negative') {
                    $sort['testCases']['negative'][] = $testCase;
                }
            }
            $sortUseCases[] = $sort;
        }

        $this->title($section, 'Info');
        $this->line($section, 'Session name', $session->name);

        $this->title($section, 'Test runs');
        foreach ($sortUseCases as $useCase) {
            $section->addListItem($useCase['name'], '0');
                    $this->line($section, 'UseCase', $useCase['name']);
            if ($useCase['testCases']['positive']) {
                $section->addListItem('Happy flow', '1', '', 'TYPE_BULLET_FILLED' );
                foreach ($useCase['testCases']['positive'] as $testCase) {
                    $this->line($section, 'TestCase', $testCase->name);

                   // $this->line($section, 'Session name', $session->name);
                    foreach ($testCase->testRuns as $testRun) {
                        $status = ($testRun->completed_at && $testRun->successful) ? 'Pass' :
                            (($testRun->completed_at && !$testRun->successful) ? 'Fail' : 'Incomplete');
                        $listItem = $section->addListItemRun(3);
                        $listItem->addText('# Run ' . $testRun->id . ' ' . $status, ['bold' => true]);

                        if($testRun->testResults){
                            foreach ($testRun->testResults as $key => $step){
                                $step_request = $step->request->toArray();
                                $step_response = $step->response->toArray();
                                //var_dump( $step->request, $step->response);
                                //dd($step, $step->request->toArray()); //->method, $step->request->path);->getRequest()
                                $section->addListItem("Step #".($key+1)." ({$step_request['method']} {$step_request['path']}) - {$status}  -  {$step->duration}ms", 4);
                                if($request['type_of_report'] == 'extended'){
                                    $section->addListItem('Request Header', 4);
                                    $section->addListItem(json_encode($step_request['headers']), 5);
                                    $section->addListItem('Request Body', 4);
                                    $section->addListItem(json_encode($step_request['body']), 5);

                                    $section->addListItem('Response Header', 4);
                                    $section->addListItem(json_encode($step_response['headers']), 5);
                                    $section->addListItem('Response Body', 4);
                                    $section->addListItem(json_encode($step_response['body']), 5);
                                }
                                $section->addLine(['weight' => 100, 'width' => 1000, 'height' => 5, 'color' => 635552]);
                            }
                        }

                    }
                }
            }
            if ($useCase['testCases']['negative']) {
                $section->addListItem('Unhappy flow', '1');
                foreach ($useCase['testCases']['negative'] as $testCase) {
                    $section->addListItem($testCase->name, '2');
                    foreach ($testCase->testRuns as $testRun) {
                        $status = ($testRun->completed_at && $testRun->successful) ? 'Pass' :
                            ($testRun->completed_at && !$testRun->successful) ? 'Fail' : 'Incomplete';
                        $section->addListItem('# Run ' . $testRun->id . ' ' . $status, '3');
                        //dd($testRun->testResults);
                        if($testRun->testResults){
                            $table = $section->addTable([
                                'borderSize' => 6,
                                'cellMargin' => 80,
                                'cellSpacing' => 50,
                            ]);
                            $style = ['bold' => true];
                            $table->addRow();
                            $table->addCell(4500)->addText(__('Step #'), $style);
                            $table->addCell(1500)->addText(__('Status'), $style);
                            $table->addCell(1500)->addText(__('Duration'), $style);
                            $table->addCell(1500)->addText(__('Attempts'), $style);
                            foreach ($testRun->testResults as $step){
                                dd($step->request);
                                $table->addRow();
                                $table->addCell()->addText("Step {$step->iteration} ()");
                                $table->addCell()->addText($status);
                            }
                        }

                    }
                }
            }
        }
        /*
        $table = $section->addTable([
            'borderSize' => 6,
            'cellMargin' => 80,
            'cellSpacing' => 50,
        ]);

        $style = ['bold' => true];
        $table->addRow();
        $table->addCell(4500)->addText(__('Test case'), $style);
        $table->addCell(1500)->addText(__('Status'), $style);
        $table->addCell(1500)->addText(__('Duration'), $style);
        $table->addCell(1500)->addText(__('Attempts'), $style);

        foreach ($session->testCases as $testCase) {
            $status = __('Incomplete');

            $lastTestRun = $testCase
                ->lastTestRun()
                ->where('session_id', $session->id)
                ->first();
            if ($lastTestRun && $lastTestRun->completed_at) {
                $status = __($lastTestRun->successful ? 'Pass' : 'Fail');
            }

            $table->addRow();
            $table->addCell()->addText($testCase->name);
            $table->addCell()->addText($status);
            $table
                ->addCell()
                ->addText($lastTestRun ? "{$lastTestRun->duration} ms" : null);
            $table
                ->addCell()
                ->addText(
                    (string) $testCase->testRuns
                        ->where('session_id', $session->id)
                        ->count()
                );
        }*/

        return $wordFile;
    }

    protected function title(
        Section $section,
        string  $text,
        int     $textSize = 16
    ): void
    {
        $section->addText(__($text), ['size' => $textSize, 'bold' => true]);
    }

    protected function line(
        Section $section,
        string  $title,
        ?string $text
    ): void
    {
        if ($text) {
            $textRun = $section->addTextRun();
            $textRun->addText(__($title) . ': ', [
                'size' => 12,
                'bold' => true,
            ]);
            $textRun->addText($text, ['size' => 12]);
        }
    }
}
