<?php

namespace App\Notifications;

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class GroupUserInvitation extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $registrationUrl = $this->registrationUrl($notifiable);

        return (new MailMessage)
            ->subject(Lang::get('Group Invitation'))
            ->line(Lang::get(
                'You have been invited to **:groupName** group.',
                ['groupName' => $notifiable->group->name]
            ))
            ->line(Lang::get(
                'In order to join the group by passing a short registration, please click on the button below.'
            ))
            ->action(Lang::get('Join'), $registrationUrl)
            ->line(Lang::get(
                '
                    Using this Invitation code **:code** with your email **:email** is mandatory to join
                    **:groupName** group on **[:itpLink](:itpLink)**.
                ',
                [
                    'code' => $notifiable->invitation_code,
                    'email' => $notifiable->email,
                    'groupName' => $notifiable->group->name,
                    'itpLink' => route('home'),
                ]
            ))
            ->line(Lang::get(
                'Please note that the invitation will expire on **:expire**.',
                ['expire' => $notifiable->expired_at]
            ))
            ->line(Lang::get('If you did not create an account, no further action is required.'));
    }

    /**
     * Get the registrationUrl URL for the given notifiable.
     *
     * @param $notifiable
     * @return string
     */
    protected function registrationUrl($notifiable)
    {
        return action(
            [RegisterController::class, 'register'],
            ['invitation_code' => $notifiable->invitation_code]
        );
    }
}
