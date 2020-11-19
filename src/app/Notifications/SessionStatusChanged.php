<?php

namespace App\Notifications;

use App\Models\Session;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SessionStatusChanged extends Notification
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
        $reason = $this->session->reason;

        return (new MailMessage())
            ->subject(
                $message = __(
                    'Compliance session moves into ":status" status',
                    ['status' => Session::getStatusName($this->session->status)]
                )
            )
            ->line($message)
            ->line($reason ? __('Reason') . ": {$reason}" : null)
            ->action(
                __('Go to session'),
                url(route('sessions.show', $this->session))
            );
    }
}
