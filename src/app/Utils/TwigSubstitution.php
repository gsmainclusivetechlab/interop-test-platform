<?php

namespace App\Utils;

use App\Models\Component;
use App\Models\Session;
use Illuminate\Support\Arr;
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
     * @param Session $session
     */
    public function __construct($testResults, $session)
    {
        $this->twig = new Environment(new ArrayLoader());
        $this->registerTwigExtensions();
        $this->data = $this->mapInto($testResults, $session);logger(json_encode($this->data));
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

                if ($this->isJsonSubstitution($replaced)) {
                    $replaced = json_decode($replaced, true) ?? $replaced;
                }
                $value = $replaced;
            }

            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * @param mixed $input
     * @return bool
     */
    protected function isJsonSubstitution($input)
    {
        return is_string($input) &&
            !is_numeric($input) &&
            is_array(json_decode($input, true)) &&
            json_last_error() == JSON_ERROR_NONE;
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
            $this->twig->addExtension(new $twigExtension());
        }
    }

    /**
     * @param $testResults
     * @param Session $session
     * @return mixed
     */
    protected function mapInto($testResults, $session)
    {
        $components = Component::all()->load('connections');
        return [
            'steps' => $testResults
                ->load('testStep')
                ->mapWithKeys(function ($item) {
                    return [
                        $item->testStep->position => [
                            'request' => $item->request
                                ? $item->request->toArray()
                                : [],
                            'response' => $item->response
                                ? $item->response->toArray()
                                : [],
                        ],
                    ];
                })
                ->toArray(),
            'env' => $session->environments,
            'components' => $components
                ->mapWithKeys(function ($item) {
                    return [
                        $item->slug => Arr::only(
                            $item->toArray(),
                            ['uuid', 'name', 'description', 'slug', 'base_url']
                        ),
                    ];
                })->toArray(),
            'mapped_urls' => $components
                ->mapWithKeys(function (Component $item) use ($session) {
                    $connectionUrls = [];
                    foreach ($item->connections as $connection) {
                        $connectionUrls[$item->slug][$connection->slug] = [
                            'sut' => route('testing.sut', [
                                $session->uuid,
                                $item->uuid,
                                $connection->uuid,
                            ]),
                            'simulator' => route('testing.simulator', [
                                $item->uuid,
                                $connection->uuid,
                            ])
                        ];
                    }
                    return $connectionUrls;
                })->toArray(),
            'session_uuid' => $session->uuid,
            'app_url' => route('home')
        ];
    }
}
