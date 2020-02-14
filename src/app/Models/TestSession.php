<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestSession extends Model
{
    use SoftDeletes;

    const DELETED_AT = 'deactivated_at';

    /**
     * @var string
     */
    protected $table = 'test_sessions';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function cases()
    {
        return $this->belongsToMany(TestCase::class)
            ->using(TestCaseTestSession::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function positiveCases()
    {
        return $this->cases()->positive();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function negativeCases()
    {
        return $this->cases()->negative();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function suites()
    {
        return $this->hasManyThrough(TestSuite::class, TestCaseTestSession::class, 'test_session_id', 'id', 'id', 'test_suite_id')
            ->distinct();
    }
}
