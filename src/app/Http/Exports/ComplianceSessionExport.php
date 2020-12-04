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
            $session->owner->groups()->first()->name ?? null
        );

        $section->addText("\n");

        foreach ($session->components as $component) {
            $this->line($section, 'SUT', $component->name);
            $this->line(
                $section,
                "{$component->name} URL",
                $session->getBaseUriOfComponent($component)
            );

            $section->addText("\n");
        }

        if ($session->environments) {
            $this->title($section, 'Environments');

            foreach ($session->environments as $key => $value) {
                $section->addText("{$key}: {$value}", ['size' => 12]);
            }

            $section->addText("\n");
        }

        $this->title($section, 'Events');
        $this->line($section, 'Creation', $session->created_at);
        $this->line($section, 'Completion', $session->completed_at);
        $this->line($section, $session->status_name, $session->closed_at);

        $section->addText("\n");

        $this->title($section, 'Questionnaire');
        foreach (
            QuestionnaireSection::getSessionQuestionnaire($session)
            as $questionnaireSection
        ) {
            $this->title($section, $questionnaireSection->name, 14);

            foreach ($questionnaireSection->questions as $key => $question) {
                $this->line($section, "Q{$key}", $question->question);
                $this->line(
                    $section,
                    "A{$key}",
                    implode(', ', $question->answers_names)
                );

                $section->addText("\n");
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
        $table->addCell(6000)->addText(__('Test case'), $style);
        $table->addCell(1500)->addText(__('Status'), $style);
        $table->addCell(1500)->addText(__('Duration'), $style);
        $table->addCell(1500)->addText(__('Attempts'), $style);

        foreach ($session->testCases as $testCase) {
            $status = __('Incompleted');

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
            $section->addText(sprintf('%s: %s', __($title), $text), [
                'size' => 12,
            ]);
        }
    }
}
