<?php

namespace App\Utils;

use App\Extensions\Twig\{Base64, Datetime, IlpPacket, Uuid};
use App\Models\Component;
use App\Models\Session;
use Exception;
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
        Uuid::class,
        Datetime::class,
        Base64::class,
        IlpPacket::class,
    ];

    /**
     * @var array
     */
    protected $data = [];

    public function __construct($testResults, Session $session)
    {
        $this->twig = new Environment(new ArrayLoader());
        $this->registerTwigExtensions();
        $this->data = $this->mapInto($testResults, $session);
    }

    public function replaceRecursive(array $input = []): array
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
    protected function isJsonSubstitution($input): bool
    {
        return is_string($input) &&
            !is_numeric($input) &&
            is_array(json_decode($input, true)) &&
            json_last_error() == JSON_ERROR_NONE;
    }

    public function replace(string $content): string
    {
        try {
            $template = $this->twig->createTemplate($content);

            return htmlspecialchars_decode(
                $template->render($this->data),
                ENT_QUOTES
            );
        } catch (Exception $e) {
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
     *
     * @return mixed
     */
    protected function mapInto($testResults, Session $session): array
    {
        $components = Component::with('connections')->get();
        $sutBaseUrls = $session->components->pluck('pivot.base_url', 'id');
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
            'file_env' => $session->fileEnvironments
                ->pluck('path', 'name')
                ->all(),
            'components' => $components
                ->mapWithKeys(function (Component $item) use ($sutBaseUrls) {
                    return [
                        $item->slug => array_merge(
                            $item->only(['name', 'slug']),
                            [
                                'base_url' => $sutBaseUrls->get($item->id),
                            ]
                        ),
                    ];
                })
                ->toArray(),
            'mapped_urls' => $this->mapUrls($components, $session),
            'session_uuid' => $session->uuid,
            'app_url' => route('home'),
        ];
    }

    protected function mapUrls($components, $session): array
    {
        $useEncryptionComponentIds = $session
            ->components()
            ->withPivotValue('use_encryption', true)
            ->pluck('id')
            ->toArray();

        return $components
            ->mapWithKeys(function (Component $component) use (
                $session,
                $useEncryptionComponentIds
            ) {
                $connectionUrls = [];
                $secure = in_array($component->id, $useEncryptionComponentIds);

                foreach ($component->connections as $connection) {
                    $urn = route(
                        $secure ? 'testing.sut' : 'testing-insecure.sut',
                        [$component->slug, $connection->slug, $session->uuid],
                        false
                    );
                    $connectionUrls[$component->slug][
                        $connection->slug
                    ] = $secure
                        ? route('home') . $urn
                        : config('app.http_base_url') . $urn;
                }

                return $connectionUrls;
            })
            ->toArray();
    }
}
