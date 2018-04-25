<?php

namespace App\Console\Commands;

use App\InstagramHelper;
use App\InstagramProfile;
use Illuminate\Console\Command;
use InstagramAPI\InstagramID;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class LikeMedia extends Command
{
	/**
	 * Base64 URL Safe Character Map.
	 *
	 * This is the Base64 "URL Safe" alphabet, which is what Instagram uses.
	 *
	 * @var string
	 *
	 * @see https://tools.ietf.org/html/rfc4648
	 */
	const BASE64URL_CHARMAP = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';

	/**
	 * Internal map of the results of all base10 digits (0-9) modulo 2.
	 *
	 * Used by the decimal-to-binary converter, to avoid costly bcmod() calls.
	 * Arranged by decimal offset, so the answer for decimal 9 is in index 9.
	 *
	 * @var string
	 */
	const BASE10_MOD2 = ['0', '1', '0', '1', '0', '1', '0', '1', '0', '1'];

	/**
	 * Runtime cached bit-value lookup table.
	 *
	 * @var array|null
	 */
	public static $bitValueTable = null;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:like {insta_username?} {media_id_type?} {media_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $profile = InstagramProfile::where('insta_username', $this->argument('insta_username'))->first();
        if ($profile != NULL) {
			$instagram = InstagramHelper::initInstagram(FALSE, $profile);
			if (InstagramHelper::login($instagram, $profile)) {

				$media_id = $this->argument('media_id');

				if ($this->argument('media_id_type') == 'code') {

					$code = $media_id;

					if (!is_string($code) || preg_match('/[^A-Za-z0-9\-_]/', $code)) {
						throw new \InvalidArgumentException('Input must be a valid Instagram shortcode.');
					}

					// Convert the base64 shortcode to a base2 binary string.
					$base2 = '';
					for ($i = 0, $len = strlen($code); $i < $len; ++$i) {
						// Find the base64 value of the current character.
						$base64 = strpos(self::BASE64URL_CHARMAP, $code[$i]);

						// Convert it to 6 binary bits (left-padded if needed).
						$base2 .= str_pad(decbin($base64), 6, '0', STR_PAD_LEFT);
					}

					// Now just convert the base2 binary string to a base10 decimal string.
					$base10 = self::base2to10($base2);

					$media_id = $base10;
				}

				$like_response = $instagram->media->like($media_id);
				$id = $this->nextJobId();
				$instagram->timeline->getUserFeed($profile->insta_user_id);
				if ($like_response->isOk()) {
					$score = Carbon::now()->timestamp * -1;
					Redis::zadd('test:like_logs', $score, $id);
				}
			}
        }
    }

	/**
	 * Get the next job ID that should be assigned.
	 *
	 * @return string
	 */
	public function nextJobId()
	{
		return (string) Redis::incr('test:like_log_id');
	}

	/**
	 * Converts a binary number of any size into a decimal string.
	 *
	 * @param string $base2 The binary bits as a string where each character is
	 *                      either "1" or "0".
	 *
	 * @throws \InvalidArgumentException If the input isn't a binary string.
	 *
	 * @return string The decimal number as a string.
	 */
	public static function base2to10(
		$base2)
	{
		if (!is_string($base2) || preg_match('/[^01]/', $base2)) {
			throw new \InvalidArgumentException('Input must be a binary string.');
		}

		// Pre-build a ~80kb RAM table with all values for bits 1-512. Any
		// higher bits than that will be generated and cached live instead.
		if (self::$bitValueTable === null) {
			self::$bitValueTable = self::buildBinaryLookupTable(512);
		}

		// Reverse the bit-sequence so that the least significant bit is first,
		// which is necessary when converting binary via its bit offset powers.
		$base2rev = strrev($base2);

		// Process each bit individually and reconstruct the base10 number.
		$base10 = '0';
		$bits = str_split($base2rev, 1);
		for ($bitPosition = 0, $len = count($bits); $bitPosition < $len; ++$bitPosition) {
			if ($bits[$bitPosition] == '1') {
				// Look up the bit value in the table or generate if missing.
				if (isset(self::$bitValueTable[$bitPosition])) {
					$bitValue = self::$bitValueTable[$bitPosition];
				} else {
					$bitValue = bcpow('2', (string) $bitPosition, 0);
					self::$bitValueTable[$bitPosition] = $bitValue;
				}

				// Now just add the bit's value to the current total.
				$base10 = bcadd($base10, $bitValue, 0);
			}
		}

		return $base10;
	}

	/**
	 * Builds a binary bit-value lookup table.
	 *
	 * @param int $maxBitCount Maximum number of bits to calculate values for.
	 *
	 * @return array The lookup table, where offset 0 has the value of bit 1,
	 *               offset 1 has the value of bit 2, and so on.
	 */
	public static function buildBinaryLookupTable(
		$maxBitCount)
	{
		$table = [];
		for ($bitPosition = 0; $bitPosition < $maxBitCount; ++$bitPosition) {
			$bitValue = bcpow('2', (string) $bitPosition, 0);
			$table[] = $bitValue;
		}

		return $table;
	}
}
