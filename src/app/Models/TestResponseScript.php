<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPosition;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestResponseScript extends Model
{
    use HasPosition;

    /**
     * @var string
     */
    protected $table = 'test_response_scripts';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'rule',
        'param',
        'value',
        'message',
    ];

    /**
     * @return array
     */
    public function getPositionGroupColumn()
    {
        return ['test_step_id'];
    }
}
