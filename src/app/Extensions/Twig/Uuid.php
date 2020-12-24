<?php

namespace App\Extensions\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Uuid extends AbstractExtension
{
    /**
     * Get functions.
     *
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('uuidv4', function (){
                return \Ramsey\Uuid\Uuid::uuid4();
            }),
        ];
    }
}
