<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPosition;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestRequestPrescript extends Model
{
    use HasPosition;

    /**
     * @var string
     */
    protected $table = 'test_request_prescripts';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'values',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'values' => 'array',
    ];

    /**
     * @return array
     */
    public function getPositionGroupColumn()
    {
        return ['test_step_id'];
    }
}
