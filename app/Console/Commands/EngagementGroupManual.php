<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\User;
use App\InstagramProfile;

class EngagementGroupManual extends Command
{

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'engagementgroup:like {media_id} {comment}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run engagement group on a media_id.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$mediaId = $this->argument('media_id');
		$comment = $this->argument('comment');

		$job = new \App\Jobs\EngagementGroup($mediaId, NULL, $comment);
		$this->line("$mediaId queued for [Engagement] with Comments? [$comment]");
		$job->onQueue("engagementgroup");
		$job->onConnection('sync');
		dispatch($job);

		return;
	}

}
