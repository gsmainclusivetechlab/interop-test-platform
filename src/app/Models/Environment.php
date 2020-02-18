<?php declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Yaml\Yaml;

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
     * @param string $line
     * @return string
     */
    public function parse($line)
    {
        $variables = Yaml::parse($this->variables);

        foreach ($variables as $key => $value) {
            $line = str_replace("{{$key}}", $value, $line);
        }

        return $line;
    }
}
