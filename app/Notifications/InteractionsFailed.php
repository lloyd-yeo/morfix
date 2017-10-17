<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;
use Carbon\Carbon;

class InteractionsFailed extends Notification
{
    use Queueable;
    protected $users;
    protected $count;
    protected $partition;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($users, $count, $partition)
    {
        $this->users = $users;
        $this->count = $count;
        $this->partition = $partition;
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
    public function toTelegram()
    {
        $date_time = Carbon::now()->toDateTimeString();
        $partition_name = "";
        if ($this->partition === 0) {
            $partition_name = "Master";
        } else {
            $partition_name = "Slave - " . $this->partition;
        }
        $text = "<b>[INTERACTIONS FAILURE][$partition_name]</b>\n\n"
            . "***** Failure Report *****\n\n"
            . "Number of users: " . $this->count . "\n"
            . "List of users affected:\n\n"
            . $this->users . "\n\n"
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
