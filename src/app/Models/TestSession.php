<?php declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Eloquent
 */
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
    public function cases()
    {
        return $this->belongsToMany(TestCase::class, 'case_id')->using(TestSessionCase::class);
    }

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
//
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function operations()
    {
        return $this->hasManyThrough(TestOperation::class, TestSessionCase::class, 'session_id', 'id', 'id', 'operation_id')->distinct();
    }
}
