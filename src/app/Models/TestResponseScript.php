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
        'rules',
        'messages',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'rules' => 'array',
        'messages' => 'array',
    ];

    /**
     * @return array
     */
    public function getPositionGroupColumn()
    {
        return ['test_step_id'];
    }
}
