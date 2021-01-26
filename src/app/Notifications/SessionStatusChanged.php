<?php

namespace App\Notifications;

use App\Models\Session;
use Arr;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SessionStatusChanged extends Notification implements ShouldQueue
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
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $session = $this->session;
        $reason = $session->reason;
        $subject = Arr::get($this->getSubjects(), $session->status);
        $message = Arr::get($this->getMessages(), $session->status);

        return (new MailMessage())
            ->subject(__($subject))
            ->line(
                __($message, [
                    'userName' => $session->owner->name,
                    'sessionName' => $session->name,
                ])
            )
            ->line($reason ? "\"{$reason}\"" : null)
            ->line(__('Please click the button below to review it.'))
            ->action(
                __('Go to session'),
                url(route('sessions.show', $session))
            );
    }

    /**
     * @return array|string[]
     */
    protected function getSubjects(): array
    {
        return [
            Session::STATUS_IN_VERIFICATION =>
                'Certification session: verification request',
            Session::STATUS_APPROVED => 'Certification session: approved',
            Session::STATUS_DECLINED => 'Certification session: declined',
        ];
    }

    /**
     * @return array|string[]
     */
    protected function getMessages(): array
    {
        return [
            Session::STATUS_IN_VERIFICATION =>
                ':userName sent his session ":sessionName" for verification.',
            Session::STATUS_APPROVED =>
                'Your session ":sessionName" was approved by the admin by the next reason:',
            Session::STATUS_DECLINED =>
                'Your session ":sessionName" was declined by the admin by the next reason:',
        ];
    }
}
