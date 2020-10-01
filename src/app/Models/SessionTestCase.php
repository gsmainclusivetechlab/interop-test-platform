<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin \Eloquent
 */
class SessionTestCase extends Pivot
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'session_test_cases';
}
