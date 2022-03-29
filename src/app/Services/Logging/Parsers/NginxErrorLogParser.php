<?php

namespace App\Services\Logging\Parsers;

use Illuminate\Support\Arr;
use Jackiedo\LogReader\Contracts\LogParser;

class NginxErrorLogParser implements LogParser
{
    protected function parseErrors(string $content): array
    {
        static $errors = [];

        if (!empty($errors)) {
            return $errors;
        }

        $lines = explode("\n", $content);

        foreach ($lines as $line) {
            if (empty($line)) {
                continue;
            }

            $error = [];

            $error['date'] = strtotime(substr($line, 0, 19));

            $line = substr($line, 20);
            $error_str = explode(': ', strstr($line, ', client:', true), 2);

            $error['message'] = $error_str[1];

            preg_match('|\[([a-z]+)\] (\d+)#(\d+)|', $error_str[0], $matches);

            $error['error_type'] = $matches[1];

            $args_str = explode(', ', substr(strstr($line, ', client:'), 2));
            $args = [];

            foreach ($args_str as $a) {
                $name_value = explode(': ', $a, 2);

                $args[$name_value[0]] = trim($name_value[1], '"');
            }

            $error = array_merge($error, $args);
            $errors[] = $error;
        }

        return $errors;
    }

    public function parseLogContent($content): array
    {
        $headerSet = $dateSet = $envSet = $levelSet = $bodySet = [];

        foreach ($this->parseErrors($content) as $index => $error) {
            $headerSet[$index] = $error['client'] . $error['request'];
            $dateSet[$index] = $error['date'];
            $envSet[$index] = config('app.env');
            $levelSet[$index] = $error['error_type'];
            $bodySet[$index]['context']['message'] = $error['message'];
            $bodySet[$index]['stack_traces'] = [];
        }

        return compact('headerSet', 'dateSet', 'envSet', 'levelSet', 'bodySet');
    }

    public function parseLogBody($content): array
    {
        return [];
    }

    public function parseLogContext($content): array
    {
        return [];
    }

    public function parseStackTrace($content): array
    {
        return [];
    }

    public function parseTraceEntry($content): array
    {
        return [];
    }
}
