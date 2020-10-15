<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\RequestCast;
use App\Casts\ResponseCast;
use App\Models\Concerns\HasPosition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @mixin \Eloquent
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
    ];

    /**
     * @var array
     */
    protected $casts = [
        'trigger' => 'array',
        'request' => RequestCast::class,
        'response' => ResponseCast::class,
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
     * @param Builder $query
     * @param Session $session
     * @return mixed
     */
    public function scopeWhereHasTestCasesOfSession($query, Session $session)
    {
        return $query->whereHas('testCase', function ($query) use ($session) {
            $query->whereExists(function ($query) use ($session) {
                $query
                    ->select(DB::raw(1))
                    ->from('session_test_cases')
                    ->where('session_test_cases.deleted_at', null)
                    ->where('session_test_cases.session_id', $session->getKey())
                    ->whereColumn('session_test_cases.test_case_id', 'test_cases.id');
            });
        });
    }
}
