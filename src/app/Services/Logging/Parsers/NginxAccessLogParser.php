<?php

namespace App\Services\Logging\Parsers;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Jackiedo\LogReader\Contracts\LogParser;

class NginxAccessLogParser implements LogParser
{
    public function parseLogContent($content)
    {
        preg_match_all(
            '/(.*)\s-\s-\s\[(.*)\]\s\"(.*)\"\s(\d+)\s(\d+)\s\"(.*)\"\s\"(.*)\"\s\"-\"/m',
            $content,
            $matches
        );

        $headerSet = $dateSet = $envSet = $levelSet = $bodySet = [];

        for ($i = 0; $i < count($matches[0]); $i++) {
            $date = Carbon::parse($matches[2][$i]);
            $headerSet[$i] = $matches[1][$i] . $date;
            $dateSet[$i] = $date->format('Y-m-d H:i:s');
            $levelSet[$i] = 'info';
            $envSet[$i] = config('app.env');
            $bodySet[
                $i
            ] = "{$matches[1][$i]}|{$matches[3][$i]}|{$matches[4][$i]}|{$matches[5][$i]}|{$matches[6][$i]}|{$matches[7][$i]}";
        }

        return compact('headerSet', 'dateSet', 'envSet', 'levelSet', 'bodySet');
    }

    public function parseLogBody($content)
    {
        $log = explode('|', $content);

        return [
            'context' => [
                'ip_address' => $log[0],
                'request' => $log[1],
                'response_code' => $log[2],
                'bytes' => $log[3],
                'url' => $log[4],
                'user_agent' => $log[5],
            ],
            'stack_traces' => [],
        ];
    }

    public function parseLogContext($content)
    {
        return [];
    }

    public function parseStackTrace($content)
    {
        return [];
    }

    public function parseTraceEntry($content)
    {
        return [];
    }
}
