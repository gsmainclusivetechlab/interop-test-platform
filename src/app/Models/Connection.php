<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin \Eloquent
 */
class Connection extends Pivot
{
    /**
     * @var string
     */
    protected $table = 'component_connections';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function specification()
    {
        return $this->hasOne(Specification::class, 'id', 'specification_id');
    }
}
