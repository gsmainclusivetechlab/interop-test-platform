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
        \App\Extensions\Twig\Base64::class,
        \App\Extensions\Twig\IlpPacket::class,
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
        $this->data = $this->mapInto($testResults, $session);
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
        $sutBaseUrls = $session->components
            ->pluck('pivot.base_url', 'id')
            ->toArray();
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
            'env' => $session->environments(),
            'components' => $components
                ->mapWithKeys(function ($item) use ($sutBaseUrls) {
                    return [
                        $item->slug => array_merge(
                            Arr::only($item->toArray(), [
                                'uuid',
                                'name',
                                'description',
                                'slug',
                            ]),
                            [
                                'base_url' => in_array(
                                    $item->id,
                                    array_keys($sutBaseUrls)
                                )
                                    ? $sutBaseUrls[$item->id]
                                    : $item->base_url,
                            ]
                        ),
                    ];
                })
                ->toArray(),
            'mapped_urls' => [
                'testing' => $this->mapUrls(
                    $components,
                    $session,
                    'testing',
                    true
                ),
                'testing-insecure' => $this->mapUrls(
                    $components,
                    $session,
                    'testing-insecure',
                    false
                ),
            ],
            'session_uuid' => $session->uuid,
            'app_url' => route('home'),
        ];
    }

    /**
     * @param $components
     * @param $session
     * @param string $route
     * @param bool $withSimulators
     * @return array
     */
    protected function mapUrls($components, $session, $route, $withSimulators)
    {
        return $components
            ->mapWithKeys(function (Component $item) use (
                $session,
                $route,
                $withSimulators
            ) {
                $connectionUrls = [];
                foreach ($item->connections as $connection) {
                    $urls = [
                        'sut' => route($route . '.sut', [
                            $session->uuid,
                            $item->uuid,
                            $connection->uuid,
                        ]),
                    ];
                    if ($withSimulators) {
                        $urls = Arr::add(
                            $urls,
                            'simulator',
                            route($route . '.simulator', [
                                $item->uuid,
                                $connection->uuid,
                            ])
                        );
                    }
                    $connectionUrls[$item->slug][$connection->slug] = $urls;
                }

                return $connectionUrls;
            })
            ->toArray();
    }
}
