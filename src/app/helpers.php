<?php

declare(strict_types=1);

if (!function_exists('json_prettify')) {
    function phpword_json_prettify(
        array $data,
        \PhpOffice\PhpWord\Element\Section $section
    ) {
        $json = json_encode($data, JSON_PRETTY_PRINT);
        $arr = explode(PHP_EOL, $json);

        $textRun = $section->addTextRun();

        foreach ($arr as $line) {
            $textRun->addTextBreak();
            $textRun->addText(preg_replace('/\s/', '&nbsp;', $line));
        }
        $textRun->addTextBreak();
    }
}
