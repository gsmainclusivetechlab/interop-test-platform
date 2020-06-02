<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class UseCase extends Model
{
    /**
     * @var string
     */
    protected $table = 'use_cases';

    /**
     * @var array
     */
    protected $fillable = ['name', 'description'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testCases()
    {
        return $this->hasMany(TestCase::class, 'use_case_id');
    }
}
