<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin \Eloquent
 */
class Session extends Model
{
    use SoftDeletes;

    const DELETED_AT = 'deactivated_at';

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

//    /**
//     * @var array
//     */
//    protected $with = [
//        'cases',
//        'lastRun',
//    ];
//
//    /**
//     * @var array
//     */
//    protected $withCount = [
//        'runs',
//        'passRuns',
//        'failRuns',
//    ];
//
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
//     */
//    public function scenario()
//    {
//        return $this->belongsTo(Scenario::class, 'scenario_id');
//    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     */
//    public function runs()
//    {
//        return $this->hasMany(TestRun::class, 'session_id')->completed();
//    }
//
//    /**
//     * @return mixed
//     */
//    public function lastRun()
//    {
//        return $this->hasOne(TestRun::class, 'session_id')->completed()->latest();
//    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
//     */
//    public function passRuns()
//    {
//        return $this->runs()->pass();
//    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
//     */
//    public function failRuns()
//    {
//        return $this->runs()->fail();
//    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
//     */
//    public function cases()
//    {
//        return $this->belongsToMany(TestCase::class, 'test_plans', 'session_id', 'case_id')
//            ->using(TestPlan::class)
//            ->withPivot(['uuid']);
//    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
//     */
//    public function positiveCases()
//    {
//        return $this->cases()->positive();
//    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
//     */
//    public function negativeCases()
//    {
//        return $this->cases()->negative();
//    }
}
