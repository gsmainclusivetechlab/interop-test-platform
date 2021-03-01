<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\RequestCast;
use App\Casts\ResponseCast;
use App\Http\Client\Request;
use App\Http\Client\Response;
use App\Models\Concerns\HasPosition;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 *
 * @property string $uuid
 * @property int $source_id
 * @property int $target_id
 * @property bool $mtls
 * @property Request $request
 * @property Response $response
 * @property Response $repeat_response
 * @property int $position
 *
 * @property Component $source
 * @property Component $target
 * @property ApiSpec $apiSpec
 */
class TestStep extends Model
{
    use HasPosition;

    /**
     * @var string
     */
    protected $table = 'test_steps';

    /**
     * @var array
     */
    protected $fillable = [
        'path',
        'method',
        'pattern',
        'trigger',
        'request',
        'response',
        'mtls',
        'repeat_max',
        'repeat_count',
        'repeat_condition',
        'repeat_response',
        'callback_origin_path',
        'callback_origin_method',
        'callback_name',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'trigger' => 'array',
        'repeat_condition' => 'array',
        'request' => RequestCast::class,
        'response' => ResponseCast::class,
        'repeat_response' => ResponseCast::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testCase()
    {
        return $this->belongsTo(TestCase::class, 'test_case_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function testRuns()
    {
        return $this->hasManyThrough(
            TestRun::class,
            TestCase::class,
            'id',
            'test_case_id',
            'test_case_id',
            'id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testResults()
    {
        return $this->hasMany(TestResult::class, 'test_step_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function source()
    {
        return $this->belongsTo(Component::class, 'source_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function target()
    {
        return $this->belongsTo(Component::class, 'target_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function apiSpec()
    {
        return $this->belongsTo(ApiSpec::class, 'api_spec_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testScripts()
    {
        return $this->hasMany(TestScript::class, 'test_step_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testSetups()
    {
        return $this->hasMany(TestSetup::class, 'test_step_id');
    }

    /**
     * @return array
     */
    public function getPositionGroupColumn()
    {
        return ['test_case_id'];
    }

    /**
     * @return array
     */
    public function getEnvironments()
    {
        $pattern = '/[^file_]env[.]([\w]*)|file_env[.]([\w]*)/';
        $subject = '';

        $subject .= json_encode($this->request->toArray()) ?: '';
        $subject .= json_encode($this->response->toArray()) ?: '';
        foreach ($this->testScripts as $testScript) {
            $subject .= json_encode($testScript->rules) ?: '';
        }
        preg_match_all($pattern, $subject, $matches);

        return [
            'env' => array_filter($matches[1]),
            'file_env' => array_filter($matches[2]),
        ];
    }

    /**
     * @return bool
     */
    public function isCallback()
    {
        return $this->callback_origin_path &&
            $this->callback_origin_method &&
            $this->callback_name;
    }
}
