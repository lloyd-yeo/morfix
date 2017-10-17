<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class InteractionsFailed extends Notification
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
    public function toTelegram($users, $count, $partition)
    {
        $date_time = Carbon::now()->toDateTimeString();
        $partition_name = "";
        if ($partition === 0) {
            $partition_name = "Master";
        } else {
            $partition_name = "Slave - " . $partition;
        }
        $text = "<b>[INTERACTIONS FAILURE][$partition_name]</b>\n\n"
            . "***** Failure Report *****\n\n"
            . "Number of users: " . $count . "\n"
            . "List of users affected:\n\n"
            . $users . "\n\n"
            . "Date/Time: " . $date_time . "\n\n"
            . "";
        $option['parse_mode'] = 'HTML';
        $telegram_msg = TelegramMessage::create();
        $telegram_msg->to('-253338893');
        $telegram_msg->options($option);
        $telegram_msg->content($text);
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
