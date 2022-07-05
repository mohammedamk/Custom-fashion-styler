<?php

namespace App\Notifications\Partners;

use App\Models\Partner;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ImportReadyNotification extends Notification
{
    use Queueable;

    public $partner;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( Partner $partner )
    {
        $this->partner = $partner;
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
            ->subject( 'Products import is ready for ' . $this->partner->name )
            ->greeting( 'Hi ' . ( $notifiable && isset( $notifiable->name ) ? $notifiable->name : '' ) . ',' )
            ->line( new HtmlString( "<strong>{$this->partner->name}</strong> has accepted your invitation and products are ready to be imported." ) )
            ->action('Import products', route( 'dashboard.partners.edit', [ 'partner' => $this->partner ] ) )
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
