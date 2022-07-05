<?php

namespace App\Notifications\Partners;

use App\Models\PartnerInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InviteNotification extends Notification
{
    use Queueable;

    /**
     * @var PartnerInvite
     */
    public $invite;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( PartnerInvite $invite )
    {

        $this->invite = $invite;

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
        return (new MailMessage)
                    ->subject( 'You have been invited to Moda Match!' )
                    ->greeting( 'Hi ' . $notifiable->name . ',' )
                    ->line('You have been invited to connect your store to Moda Match.')
                    ->action('Connect my store', $this->invite->url)
                    ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
