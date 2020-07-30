<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Group extends Model
{
    /**
     * @var string
     */
    protected $table = 'groups';

    /**
     * @var array
     */
    protected $fillable = ['name', 'domain', 'description'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(
            User::class,
            'group_members',
            'group_id',
            'user_id'
        );
    }
}
