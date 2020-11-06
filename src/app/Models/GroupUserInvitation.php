<?php declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use App\Notifications\GroupUserInvitation as InvitationNotification;

/**
 * Class GroupUserInvitation
 * @package App\Models
 */
class GroupUserInvitation extends Model
{
    use Notifiable;

    const STATUS_ACTIVE = 'Active';
    const STATUS_EXPIRED = 'Expired';

    const DEFAULT_EXPIRE_INVITATION = 432000;

    /**
     * @var string
     */
    protected $table = 'group_user_invitations';

    /**
     * @var array
     */
    protected $fillable = ['email', 'invitation_code', 'expired_at'];

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
        $this->attributes['expired_at'] = Carbon::now()->addSeconds($ttl)->toDateTimeString();
    }

    /**
     * Get invitation status
     */
    public function getStatusAttribute()
    {
        return 0 < Carbon::now()->diffInSeconds($this->expired_at, false)
            ? self::STATUS_ACTIVE
            : self::STATUS_EXPIRED;
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
