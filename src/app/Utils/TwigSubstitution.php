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
        \App\Extensions\Twig\Datetime::class,
    ];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param $testResults
     * @param array $envs
     */
    public function __construct($testResults, $envs = [])
    {
        $this->twig = new Environment(new ArrayLoader());
        $this->registerTwigExtensions();
        $this->data = $this->mapInto($testResults, $envs);
    }

    /**
     * @param array $input
     * @return array
     */
    public function replaceRecursive(array $input = [])
    {
        $result = [];
        foreach ($input as $key => $value) {
            if (is_string($key)) {
                $key = $this->replace($key);
            }

            if (is_array($value)) {
                $value = $this->replaceRecursive($value);
            } elseif (is_string($value)) {
                $replaced = $this->replace($value);

                $value = is_numeric($replaced)
                    ? $replaced
                    : json_decode($replaced, true) ?? $replaced;
            }

            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * @param string $content
     * @return string
     */
    public function replace(string $content)
    {
        try {
            $template = $this->twig->createTemplate($content);

            return htmlspecialchars_decode($template->render($this->data));
        } catch (\Exception $e) {
            return $content;
        }
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
     * @param array $envs
     * @return mixed
     */
    protected function mapInto($testResults, $envs)
    {
        return [
            'steps' => $testResults->load('testStep')
                ->mapWithKeys(function ($item) {
                    return [
                        $item->testStep->position => [
                            'request' => $item->request ? $item->request->toArray() : [],
                            'response' => $item->response ? $item->response->toArray() : [],
                        ]
                    ];
                })
                ->toArray(),
            'env' => $envs,
        ];
    }
}
