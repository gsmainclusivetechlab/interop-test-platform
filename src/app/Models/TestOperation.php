<?php declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class TestOperation extends Model
{
    /**
     * @var string
     */
    protected $table = 'test_operations';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function steps()
    {
        return $this->hasMany(TestOperationStep::class, 'operation_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cases()
    {
        return $this->hasMany(TestCase::class, 'operation_id');
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
}
