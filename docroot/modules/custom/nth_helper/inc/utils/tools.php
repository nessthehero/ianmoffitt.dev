<?php

	namespace Nth\Utils;

	class Tools
	{

		/**
		 * Removes several HTML tags from a string
		 *
		 * @param $s
		 * @param $tags
		 *
		 * @return mixed|string|string[]|null
		 */
		public static function removeTags($s, $tags)
		{
			if (!empty($tags)) {
				foreach ($tags as $tag) {
					$s = self::removeTag($s, $tag);
				}
			}

			return $s;
		}

		/**
		 * Removes a specific HTML tag from a string.
		 *
		 * @param $s
		 * @param $tag
		 *
		 * @return string|string[]|null
		 */
		public static function removeTag($s, $tag)
		{
			$pattern = '/<' . $tag . '(.*?)>(.*?)<\/' . $tag . '>/i';
			$replacement = '${2}';

			return preg_replace($pattern, $replacement, $s);
		}

		/**
		 * Returns the first entry that passes an isset() test.
		 *
		 * Each entry can either be a single value: $value, or an array-key pair:
		 * $array, $key.  If all entries fail isset(), or no entries are passed,
		 * then nvl() will return null.
		 *
		 * $array must be an array that passes isset() on its own, or it will be
		 * treated as a standalone $value.  $key must be a valid array key, or
		 * both $array and $key will be treated as standalone $value entries. To
		 * be considered a valid key, $key must pass:
		 *
		 *     is_null($key) || is_string($key) || is_int($key) || is_float($key)
		 *         || is_bool($key)
		 *
		 * If $value is an array, it must be the last entry, the following entry
		 * must be a valid array-key pair, or the following entry's $value must
		 * not be a valid $key.  Otherwise, $value and the immediately following
		 * $value will be treated as an array-key pair's $array and $key,
		 * respectfully.  See above for $key validity tests.
		 * @return array|false|mixed|null
		 */
		public static function nvl(/* [(array $array, $key) | $value]... */)
		{
			$count = func_num_args();

			for ($i = 0; $i < $count - 1; $i++) {
				$arg = func_get_arg($i);

				if (!isset($arg)) {
					continue;
				}

				if (is_array($arg)) {

					$key = func_get_arg($i + 1);

					if (is_null($key) || is_string($key) || is_int($key) || is_float($key) || is_bool($key)) {

						if (isset($arg[$key])) {
							// print_r($arg);
							return $arg[$key];
						}

						$i++;
						continue;
					}
				}

				return $arg;
			}

			if ($i < $count) {
				return func_get_arg($i);
			}

			return null;
		}

		/**
		 * Find a value in a multidimensional array
		 *
		 * @param string $elem  needle
		 * @param array  $array multidimensional haystack
		 *
		 * @return bool         boolean if value was found or not
		 */
		public static function in_multiarray($elem, $array)
		{
			foreach ($array as $key => $value) {
				if ($value == $elem) {
					return true;
				} else if (is_array($value)) {
					if (self::in_multiarray($elem, $value))
						return true;
				}
			}

			return false;
		}

		/**
		 * Generate a render array to send to Twig
		 *
		 * @param $content
		 *
		 * @return array
		 */
		public static function ra($content)
		{

			$tmp = $content;

			if (is_array($content)) {
				$tmp = implode(' ', $content);
			}

			// If you use #markup, it will filter out bad tags, but #children does not
			return array(
				'#children' => $tmp
			);
		}

		/**
		 * Returns key of array, or default value given. Mostly used to get query strings.
		 *
		 * @param array  $q       Array
		 * @param string $i       key of array
		 * @param string $default Fallback value
		 *
		 * @return string          value from array, or fallback
		 */
		public static function qs($q = array(), $i = '', $default = '')
		{

			if (!empty($q) && !empty($i)) {

				return strtolower(self::nvl(self::nvl($q, $i), $default, ''));

			} else {

				return $default;

			}

		}

		/**
		 * Generate a safe slug from any string.
		 *
		 * @param $str
		 *
		 * @return string
		 */
		public static function gen_slug($str)
		{
			# special accents
			$a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'Ð', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', '?', '?', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', '?', '?', 'L', 'l', 'N', 'n', 'N', 'n', 'N', 'n', '?', 'O', 'o', 'O', 'o', 'O', 'o', 'Œ', 'œ', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'Š', 'š', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Ÿ', 'Z', 'z', 'Z', 'z', 'Ž', 'ž', '?', 'ƒ', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', '?', '?', '?', '?', '?', '?');
			$b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');

			return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), str_replace($a, $b, $str)));
		}

		/**
		 * Helper that determines if an array has numeric keys or associative keys.
		 *
		 * @param array $arr
		 *
		 * @return bool
		 */
		public static function isAssoc(array $arr)
		{
			if (array() === $arr) return false;

			return array_keys($arr) !== range(0, count($arr) - 1);
		}

		public static function partition(array $list, $p)
		{

			$listlen = count( $list );
			$partlen = floor( $listlen / $p );
			$partrem = $listlen % $p;
			$partition = array();
			$mark = 0;
			for ($px = 0; $px < $p; $px++) {
				$incr = ($px < $partrem) ? $partlen + 1 : $partlen;
				$partition[$px] = array_slice( $list, $mark, $incr );
				$mark += $incr;
			}
			return $partition;

		}

		/**
		 * Helper that removes all HTML comments from a string.
		 * Useful for removing template debugging comments from rendered nodes.
		 *
		 * @param string $content
		 *
		 * @return string
		 */
		public static function remove_html_comments($content = '')
		{
			return trim(preg_replace('/<!--(.|\s)*?-->/', '', $content));
		}

		/**
		 * Helper to remove all comments, new lines, returns, etc. from a chunk
		 * of markup.
		 *
		 * @param $input
		 *
		 * @return string
		 */
		public static function collapse($input)
		{
			$output = str_replace(array("\r\n", "\r"), "\n", $input);
			$lines = explode("\n", $output);
			$new_lines = array();

			foreach ($lines as $i => $line) {
				if (!empty($line))
					$new_lines[] = trim($line);
			}

			return remove_html_comments(implode($new_lines));
		}

		/**
		 * Alters a user-entered YouTube value to ensure it fits to our restrictions.
		 *
		 * @param      $value
		 * @param bool $asUrl
		 *
		 * @return mixed
		 */
		public static function fixYoutube($value, $asUrl = true)
		{

			if (empty($value)) {
				return null;
			}

			// Make changes here if this isn't just a URL translation (just the video ID)
			if (!$asUrl) {

				$altered = false;

				// Check for V URL
				$check = '?v=';
				$pos = strpos($value, $check);
				if ($pos !== false) {
					$altered = true;
					$value = substr($value, $pos + strLen($check));
				}

				// Check for Embed URL
				$check = 'embed/';
				$pos = strpos($value, $check);
				if ($pos !== false) {
					$altered = false;
					$value = substr($value, $pos + strLen($check));
				}

				// Check for Short URL
				if (!$altered) {
					$check = 'youtu.be/';
					$pos = strpos($value, $check);
					if ($pos !== false) {
						$value = substr($value, $pos + strLen($check));
					}
				}

				return $value;
			}

			// set to true if you want /embed/ URLs, false if you want /watch?v= URLs
			$useEmbed = false;

			$hasShortUrl = (strpos($value, 'youtu.be') !== false);
			$hasVParam = (strpos($value, 'watch?v') !== false);
			$hasEmbed = (strpos($value, 'embed/') !== false);

			if ($hasVParam && strpos($value, '&')) {
				$value = substr($value, 0, strpos($value, '&'));
			}

			// Expand short URL (e.g. https://youtu.be/C0DPdy98e4c)
			if ($hasShortUrl && !$hasVParam && !$hasEmbed) {

				if ($useEmbed) {
					$value = str_replace('youtu.be/', 'www.youtube.com/embed/', $value);
				} else {
					$value = str_replace('youtu.be/', 'www.youtube.com/watch?v=', $value);
				}

				// Require embed if used using full URL
			} else if ($hasVParam && $useEmbed) {
				$value = str_replace('watch?v=', 'embed/', $value);
			} else if ($hasEmbed && !$useEmbed) {
				$value = str_replace('embed/', 'watch?v=', $value);
			} else if (!$hasShortUrl && !$hasVParam && !$hasEmbed) {
				if ($useEmbed) {
					$value = 'https://www.youtube.com/embed/' . $value;
				} else {
					$value = 'https://www.youtube.com/watch?v=' . $value;
				}
			}

			return $value;

		}

	}
