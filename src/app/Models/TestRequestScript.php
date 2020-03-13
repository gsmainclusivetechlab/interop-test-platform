<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPosition;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestRequestScript extends Model
{
    use HasPosition;

    /**
     * @var string
     */
    protected $table = 'test_request_scripts';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
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
