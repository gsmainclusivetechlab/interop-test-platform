<?php

namespace App\Http\Exports;

use App\Models\QuestionnaireSection;
use App\Models\Session;
use App\Models\TestRun;
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
                    (string) $testCase->testRuns
                        ->where('session_id', $session->id)
                        ->count()
                );
        }

        return $wordFile;
    }

    protected function title(
        Section $section,
        string $text,
        int $textSize = 16
    ): void {
        $section->addText(__($text), ['size' => $textSize, 'bold' => true]);
    }

    protected function line(
        Section $section,
        string $title,
        ?string $text
    ): void {
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
