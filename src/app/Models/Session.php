<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Session extends Model
{
    use HasUuid;

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
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function components()
    {
        return $this->belongsToMany(Component::class, 'session_components', 'session_id', 'component_id')
            ->withPivot(['base_url']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testRuns()
    {
        return $this->hasMany(TestRun::class, 'session_id');
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
    public function testCases()
    {
        return $this->belongsToMany(TestCase::class, 'session_test_cases', 'session_id', 'test_case_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function testSteps()
    {
        return $this->belongsToMany(TestStep::class, 'session_test_cases', 'session_id', 'test_case_id', 'id', 'test_case_id');
    }
}
