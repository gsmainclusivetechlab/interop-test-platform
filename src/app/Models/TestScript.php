<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPosition;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestScript extends Model
{
    use HasPosition;

    const TYPE_REQUEST = 'request';
    const TYPE_RESPONSE = 'response';

    /**
     * @var string
     */
    protected $table = 'test_scripts';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'rules',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'rules' => 'array',
    ];

    /**
     * @return array
     */
    public function getPositionGroupColumn()
    {
        return ['test_step_id'];
    }
}
