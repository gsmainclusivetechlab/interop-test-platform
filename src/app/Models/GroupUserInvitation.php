<?php declare(strict_types=1);

namespace App\Models;

use Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Notifications\GroupUserInvitation as InvitationNotification;

/**
 * Class GroupUserInvitation
 * @package App\Models
 */
class GroupUserInvitation extends Model
{
    use Notifiable;

    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';

    /**
     * @var string
     */
    protected $table = 'group_user_invitations';

    /**
     * @var array
     */
    protected $fillable = ['email', 'expired_at'];

    /**
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::saving(function ($model) {
            $model->attributes['invitation_code'] = Str::random(15);
        });
    }

    /**
     * @return array
     */
    public static function getBehaviorNames()
    {
        return [
            static::STATUS_ACTIVE => __('Active'),
            static::STATUS_EXPIRED => __('Expired'),
        ];
    }

    /**
     * @return BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * @param $ttl
     */
    public function setExpiredAtAttribute($ttl)
    {
        $this->attributes['expired_at'] = now()
            ->addSeconds($ttl)
            ->toDateTimeString();
    }

    /**
     * Get is active status
     *
     * @return bool
     */
    public function isActive()
    {
        return 0 < now()->diffInSeconds($this->expired_at, false);
    }

    /**
     * Get invitation status
     */
    public function getStatusAttribute()
    {
        return $this->isActive()
            ? Arr::get(
                static::getBehaviorNames(),
                self::STATUS_ACTIVE,
                self::STATUS_ACTIVE
            )
            : Arr::get(
                static::getBehaviorNames(),
                self::STATUS_EXPIRED,
                self::STATUS_EXPIRED
            );
    }

    /**
     * Send the email invitation notification.
     *
     * @return void
     */
    public function sendEmailInvitationNotification()
    {
        $this->notify(new InvitationNotification());
    }
}
