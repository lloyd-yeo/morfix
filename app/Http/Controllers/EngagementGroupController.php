<?php

namespace App\Http\Controllers;

use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\EngagementGroupJob;
use App\InstagramHelper;
use App\InstagramProfile;
use App\InstagramProfileMedia;
use Log;

class EngagementGroupController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{

		Log::info("[ENGAGEMENT GROUP INDEX] " . Auth::user()->email . " is accessing engagement group index page. Loading up pictures...");

		$instagram_profiles = InstagramProfile::where('email', Auth::user()->email)
		                                      ->take(Auth::user()->num_acct)
		                                      ->get();

		$instagram = InstagramHelper::initInstagram();

		foreach ($instagram_profiles as $ig_profile) {
			if (InstagramHelper::login($instagram, $ig_profile, 0)) {

				$items = $instagram->timeline->getSelfUserFeed()->getItems();

				Log::error("[ENGAGEMENT GROUP INDEX] GET ITEMS " . Auth::user()->email . " " . var_export($items, TRUE));

				foreach ($items as $item) {
					try {
						$image_url = "";
						if (is_null($item->getImageVersions2())) {
							//is carousel media
							$image_url = $item->getCarouselMedia()[0]
								             ->getImageVersions2()
								             ->getCandidates()[0]
								->getUrl();
						} else {
							$image_url = $item->getImageVersions2()
							                  ->getCandidates()[0]
								->getUrl();
						}

						try {
							if (InstagramProfileMedia::where('media_id', $item->getPk())->first() == NULL) {
								$new_profile_post                 = new InstagramProfileMedia;
								$new_profile_post->insta_username = $ig_profile->insta_username;
								$new_profile_post->media_id       = $item->getPk();
								$new_profile_post->image_url      = $image_url;
								$new_profile_post->code           = $item->getCode();
								$new_profile_post->created_at     = \Carbon\Carbon::createFromTimestamp($item->getTakenAt());
								$new_profile_post->save();
							}
						}
						catch (\Exception $ex) {
							Log::error("[ENGAGEMENT GROUP INDEX] " . Auth::user()->email . " " . $ex->getMessage());
							Log::error("[ENGAGEMENT GROUP INDEX] " . Auth::user()->email . " " . $ex->getTraceAsString());
						}
					}
					catch (\ErrorException $e) {
						Log::error("[ENGAGEMENT GROUP INDEX] " . Auth::user()->email . " " . $e->getMessage());
						Log::error("[ENGAGEMENT GROUP INDEX] " . Auth::user()->email . " " . $e->getTraceAsString());
						$this->profile->error_msg = $e->getMessage();
						$this->profile->save();
					}
					catch (\Exception $ex) {
						Log::error("[ENGAGEMENT GROUP INDEX] " . Auth::user()->email . " " . $ex->getMessage());
						Log::error("[ENGAGEMENT GROUP INDEX] " . Auth::user()->email . " " . $ex->getTraceAsString());
					}
				}
			}
		}

		return view('engagement-group.index', [
			'user_ig_profiles' => $instagram_profiles,
		]);
	}

	public function profile(Request $request, $id)
	{
		Log::info("[ENGAGEMENT GROUP PROFILE UPLOADS] Retrieving user_insta_profile with ID of: " . $id);

		$ig_profile = InstagramProfile::find($id);

		if ($ig_profile != NULL) {
			Log::info("[ENGAGEMENT GROUP PROFILE UPLOADS] Retrieved user_insta_profile: " . $ig_profile->insta_username);
		} else {
			Log::info("[ENGAGEMENT GROUP PROFILE UPLOADS] Missing user_insta_profile: " . $id);
		}

		$medias = InstagramProfileMedia::where('insta_username', $ig_profile->insta_username)
		                               ->orderBy('created_at', 'desc')
		                               ->get();

		if ($medias->count() > 0) {
			Log::info("[ENGAGEMENT GROUP PROFILE UPLOADS] Retrieved media count of: " . $medias->count());
		} else {
			if ($ig_profile != NULL) {
				Log::info("[ENGAGEMENT GROUP PROFILE UPLOADS] No media retrieved for user_insta_profile: " . $ig_profile->insta_username);
			} else {
				Log::info("[ENGAGEMENT GROUP PROFILE UPLOADS] No media retrieved for missing user_insta_profile: " . $id);
			}
		}

		return view('engagement-group.profile', [
			'ig_profile' => $ig_profile,
			'medias'     => $medias,
		]);
	}

	public function schedule(Request $request, $media_id)
	{
		$user = User::where('email', Auth::user()->email)->first();
		if ($user->engagement_quota > 0) {
			$engagement_group_job = EngagementGroupJob::where('media_id', '=', $media_id)->first();
			if ($engagement_group_job === NULL) {
				$engagement_group_job           = new EngagementGroupJob;
				$engagement_group_job->media_id = $media_id;
				$engagement_group_job->engaged  = 0;
				if ($engagement_group_job->save()) {
					$user->engagement_quota = $user->engagement_quota - 1;
					$user->save();
					$job = new \App\Jobs\EngagementGroup($media_id, $request->input('profile_id'));
					$job->onQueue('engagementgroup');
					dispatch($job);
				}

				return Response::json([ "success" => TRUE, 'message' => "Your photo has been sent for engagement group. Expect a increase in engagement." ]);
			} else {
				$engagement_group_job->engaged = 0;
				$engagement_group_job->save();

				return Response::json([ "success" => FALSE, 'message' => "Your image has already been sent for engagement before." ]);
			}
		} else {
			return Response::json([ "success" => FALSE, 'message' => "You've ran out of engagement credits. Do try again tomorrow." ]);
		}
	}
}
