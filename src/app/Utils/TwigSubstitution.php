<?php

namespace App\Utils;

use Twig\Environment;
use Twig\Loader\ArrayLoader;

/**
 * Class TwigSubstitution
 * @package App\Utils
 */
class TwigSubstitution
{
    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var array
     */
    protected $twigExtensions = [
        \App\Extensions\Twig\Uuid::class,
    ];

    /**
     * @var array
     */
    protected $testResults;

    /**
     * @param $testResults
     */
    public function __construct($testResults)
    {
        $this->twig = new Environment(new ArrayLoader());
        $this->registerTwigExtensions();
        $this->testResults = $this->mapResults($testResults);
    }

    /**
     * @param string $content
     * @return string
     */
    public function replace(string $content)
    {
        return preg_replace_callback(
            '/\{\{([^\}]+)\}\}/',
            function ($matches) use ($content) {
                try {
                    $template = $this->twig->createTemplate($matches[0]);

                    return $template->render(['steps' => $this->testResults]);
                } catch (\Exception $e) {
                    return $content;
                }
            },
            $content
        );
    }

    /**
     * @return void
     */
    protected function registerTwigExtensions()
    {
        foreach ($this->twigExtensions as $twigExtension) {
            $this->twig->addExtension(new $twigExtension);
        }
    }

    /**
     * @param $testResults
     * @return mixed
     */
    protected function mapResults($testResults)
    {
        return $testResults->load('testStep')
            ->mapWithKeys(function ($item) {
                return [
                    $item->testStep->position => [
                        'request' => $item->request ? $item->request->toArray() : [],
                        'response' => $item->response ? $item->response->toArray() : [],
                    ]
                ];
            })
            ->toArray();
    }
}
