<?php

namespace App\Notifications;

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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

        return (new MailMessage())
            ->subject(__('Group Invitation'))
            ->line(
                __('You have been invited to the '**:groupName**' group.', [
                    'groupName' => $notifiable->group->name,
                ])
            )
            ->line(
                __(
                    'In order to accept the invitation, you must register an account. To do so, please click on the button below.'
                )
            )
            ->action(__('Join'), $registrationUrl)
            ->line(
                __(
                    '
                    You must use this Invitation code '**:code**' with your email '**:email**' to join
                    '**:groupName**': **[:itpLink](:itpLink)**.
                ',
                    [
                        'code' => $notifiable->invitation_code,
                        'email' => $notifiable->email,
                        'groupName' => $notifiable->group->name,
                        'itpLink' => route('home'),
                    ]
                )
            )
            ->line(
                __(
                    'Please note that the invitation will expire on **:expire**.',
                    ['expire' => $notifiable->expired_at]
                )
            )
            ->line(
                __(
                    'If you do not wish to create an account, no further action is required.'
                )
            );
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
            [
                'email' => $notifiable->email,
                'invitationCode' => $notifiable->invitation_code,
            ]
        );
    }
}
