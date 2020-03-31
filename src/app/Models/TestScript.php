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

    /**
     * @var string
     */
    protected $table = 'test_scripts';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'group',
        'rules',
        'messages',
        'attributes',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'rules' => 'array',
        'messages' => 'array',
        'attributes' => 'array',
    ];

    /**
     * @return array
     */
    public function getPositionGroupColumn()
    {
        return ['test_step_id'];
    }
}
