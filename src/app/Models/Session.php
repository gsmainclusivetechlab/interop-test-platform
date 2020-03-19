<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Session extends Model
{
    /**
     * @var string
     */
    protected $table = 'sessions';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'scenario_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scenario()
    {
        return $this->belongsTo(Scenario::class, 'scenario_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testRuns()
    {
        return $this->hasMany(TestRun::class, 'session_id')->completed();
    }

    /**
     * @return mixed
     */
    public function lastTestRun()
    {
        return $this->hasOne(TestRun::class, 'session_id')->completed()->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function passedTestRuns()
    {
        return $this->testRuns()->passed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function failureTestRuns()
    {
        return $this->testRuns()->failure();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function testCases()
    {
        return $this->belongsToMany(TestCase::class, 'test_plans', 'session_id', 'test_case_id')
            ->using(TestPlan::class)
            ->withPivot(['uuid']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function positiveTestCases()
    {
        return $this->testCases()->positive();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function negativeTestCases()
    {
        return $this->testCases()->negative();
    }
}
