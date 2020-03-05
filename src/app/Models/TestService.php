<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestService extends Model
{
    /**
     * @var string
     */
    protected $table = 'test_services';

    /**
     * @var string
     */
    protected $primaryKey = 'component_id';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function component()
    {
        return $this->belongsTo(TestComponent::class, 'component_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function api()
    {
        return $this->belongsTo(ApiService::class, 'api_id');
    }
}
