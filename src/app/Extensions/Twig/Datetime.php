<?php

namespace App\Extensions\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Datetime extends AbstractExtension
{
    /**
     * Get functions.
     *
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('new_date_iso8601', function () {
                return now()->toIso8601String();
            }),
            new TwigFunction('new_date_iso8601_zulu', function () {
                return now()->toIso8601ZuluString('m');
            }),
            new TwigFunction('new_date_rfc2822', function () {
                return now()->toRfc2822String();
            }),
            new TwigFunction('new_date_rfc7231', function () {
                return now()->toRfc7231String();
            }),
        ];
    }
}
