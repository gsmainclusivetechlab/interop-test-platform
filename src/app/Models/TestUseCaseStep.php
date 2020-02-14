<?php declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class TestUseCaseStep extends Model
{
    /**
     * @var string
     */
    protected $table = 'test_use_cases_steps';

    /**
     * @var array
     */
    protected $fillable = [
        'path',
        'method',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function useCase()
    {
        return $this->belongsTo(TestUseCase::class, 'use_case_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function connection()
    {
        return $this->belongsTo(TestPlatformConnection::class, 'connection_id');
    }
}
