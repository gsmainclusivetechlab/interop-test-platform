<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\InteractsWithHttpRequest;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestDatum extends Model
{
    use InteractsWithHttpRequest;

    /**
     * @var string
     */
    protected $table = 'test_data';

    /**
     * @var bool
     */
    public $incrementing = false;
}
