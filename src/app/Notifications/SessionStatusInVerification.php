<?php

namespace App\Notifications;

use App\Models\Session;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SessionStatusInVerification extends Notification
{
    use Queueable;

    /** @var Session */
    protected $session;

    /**
     * Create a new notification instance.
     *
     * @param Session $session
     *
     * @return void
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

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
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject(
                __('Compliance session moves into "In Verification" status')
            )
            ->line(
                __('Compliance session moves into "In Verification" status.')
            )
            ->action(
                __('Session summary page '),
                url(route('admin.compliance-sessions.show', $this->session))
            );
    }
}
