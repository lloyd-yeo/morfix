<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;
use App\InstagramProfileMedia;

class EngagementGroupFailed extends Notification
{
	use Queueable;

	protected $media_id;
	protected $group_chat_id;
	protected $telegram_msg_option;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct($media_id)
	{
		$this->media_id = $media_id;
		$this->group_chat_id = '-253338893';
		$this->telegram_msg_option = array();
		$this->telegram_msg_option['parse_mode'] = 'HTML';
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed $notifiable
	 * @return array
	 */
	public function via($notifiable)
	{
		return [ TelegramChannel::class ];
	}

	/**
	 *Send Telegram message to group chat
	 */
	public function toTelegram($extra_variable)
	{
		$failed_media_meta = InstagramProfileMedia::where('media_id', $this->media_id)->first();

		$date_time = Carbon::now()->toDateTimeString();
		$text = "<b>[ENGAGEMENT GROUP FAILURE]</b>\n\n"
				. "***** Failure Report *****\n\n"
				. "Media ID: " . $failed_media_meta->media_id . "\n"
				. "Instagram URL: https://www.instagram.com/p/" . $failed_media_meta->code . "\n"
				. "Profile: " . $failed_media_meta->insta_username . "\n\n"
				. "Date/Time: " . $date_time . "\n\n"
				. "";

		$telegram_msg = TelegramMessage::create();
		$telegram_msg->to($this->group_chat_id);
		$telegram_msg->options($this->telegram_msg_option);
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
