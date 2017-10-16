<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class SlaveInteractionsFailed extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    /**
     *Send Telegram message to group chat
     */

    public function toTelegram($users)
    {

        $option['parse_mode'] = 'HTML';
//
        $telegram_msg = TelegramMessage::create();
        $telegram_msg->to('-253338893');
        $telegram_msg->options($option);
        $telegram_msg->content("SELECT * FROM insta_affiliate.user_interaction_failed where insta_username = ' '\n" . $users);
        return $telegram_msg;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
