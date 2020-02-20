<?php declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class Environment extends Model
{
    /**
     * @var string
     */
    protected $table = 'environments';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'variables',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'variables' => 'array',
    ];

    /**
     * @param string $line
     * @return string
     */
    public function parse($line)
    {
        foreach ($this->variables as $key => $value) {
            $line = str_replace("{{$key}}", $value, $line);
        }

        return $line;
    }
}
