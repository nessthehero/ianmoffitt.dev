<?php

	// General functions to accomplish common tasks
	// Also includes other helper functions

	// Drupal class includes
	use Drupal\image\Entity\ImageStyle;
	use Drupal\media\Entity\Media;
	use Drupal\file\Entity\File;
	use Drupal\core\DateTime\DrupalDateTime;

	// Content related helpers
	include_once('content.php');
	include_once('shared.php');

	// Preprocessing related helpers
	include_once('functions/paragraphs.php');
	include_once('functions/nodes.php');
	include_once('functions/links.php');
	include_once('functions/taxonomy.php');

	// Entity Finders
	include_once('finder.php'); // Generic Finder
	include_once('news.php'); // Articles
	include_once('events.php'); // Events
	include_once('programs.php'); // Programs
	/**
	 * Removes several HTML tags from a string
	 *
	 * @param $s
	 * @param $tags
	 *
	 * @return string
	 */
	function removeTags($s, $tags)
	{
		if (!empty($tags)) {
			foreach ($tags as $tag) {
				$s = removeTag($s, $tag);
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
	 * @return string
	 */
	function removeTag($s, $tag)
	{
		$pattern = '/<' . $tag . '(.*?)>(.*?)<\/' . $tag . '>/i';
		$replacement = '${2}';

		return preg_replace($pattern, $replacement, $s);
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
	function qs($q = array(), $i = '', $default = '')
	{

		if (!empty($q) && !empty($i)) {

			return strtolower(nvl(nvl($q, $i), $default, ''));

		} else {

			return $default;

		}

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
	 */
	function nvl(/* [(array $array, $key) | $value]... */)
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
	function in_multiarray($elem, $array)
	{
		foreach ($array as $key => $value) {
			if ($value == $elem) {
				return true;
			} else if (is_array($value)) {
				if ($this->in_multiarray($elem, $value))
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
	function ra($content)
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

	// Following image functions are used for simple image fields, NOT media fields set to images.

	/**
	 * Generate a render array of an image from an image field
	 *
	 * @param        $node
	 * @param        $field
	 * @param string $style
	 *
	 * @return array
	 */
	function image($node, $field, $style = '')
	{

		$img = $node->get($field);
		$builder = '';

		$alt = '';
		if (!empty($img->alt)) {
			$alt = $img->alt;
		};

		$imagestyleurl = image_url($node, $field, $style);

		if (!empty($imagestyleurl)) {
			$builder = sprintf('<img src="%s" alt="%s" />',
				$imagestyleurl,
				$alt
			);
		}

		return ra($builder);

	}

	/**
	 * Generate the URL of an image from a typical image field.
	 *
	 * @param        $node
	 * @param        $field
	 * @param string $style
	 *
	 * @return string
	 */
	function image_url($node, $field, $style = '')
	{

		$imageurl = '';

		if (!$node->get($field)->isEmpty()) {

			$uri = $node->get($field)->entity->getFileUri();

			if (!empty($style)) {

				$imageStyle = ImageStyle::load($style);

				if (!empty($imageStyle)) {
					$imageurl = ImageStyle::load($style)->buildUrl($uri);
				} else {
					$imageurl = file_create_url($uri);
				}

			} else {

				$imageurl = file_create_url($uri);

			}

		}

		return $imageurl;

	}

	/**
	 * Provides an array of image urls. Use for repeating image fields.
	 *
	 * @param        $node
	 * @param        $field
	 * @param string $style
	 *
	 * @return array
	 */
	function multi_image_urls($node, $field, $style = '')
	{

		$imageurl = array();

		if (!$node->get($field)->isEmpty()) {

			$images = $node->get($field);

			foreach ($images as $key => $image) {

				$uri = $image->entity->getFileUri();

				if (!empty($style)) {

					$imageurl[] = ImageStyle::load($style)->buildUrl($uri);

				} else {

					$imageurl[] = file_create_url($uri);

				}
			}

		}

		return $imageurl;

	}

	function multi_image_style_urls($node, $field, $style = array())
	{

		$imageurl = array();

		if (!$node->get($field)->isEmpty()) {

			$images = $node->get($field);

			foreach ($images as $ikey => $image) {

				$uri = $image->entity->getFileUri();

				if (!empty($style)) {

					foreach ($style as $skey => $value) {

						$imageurl[$ikey]['index'][$skey] = ImageStyle::load($value)->buildUrl($uri);
						$imageurl[$ikey]['named'][$value] = ImageStyle::load($value)->buildUrl($uri);

					}

				} else {

					$imageurl[$ikey]['index'][0] = file_create_url($uri);
					$imageurl[$ikey]['named']['default'] = file_create_url($uri);

				}

			}

		}

		return $imageurl;

	}

	// Following functions are for Media entity references.

	/**
	 * Generate the URL of an image from a media entity reference.
	 *
	 * @param        $node
	 * @param        $field
	 * @param string $style
	 *
	 * @return string
	 */
	function media_url($node, $field, $style = '')
	{

		$imageurl = '';

		if (!$node->get($field)->isEmpty()) {

			$media = Media::load($node->get($field)->target_id);

			$uri = $media->image->entity->getFileUri();

			if (!empty($style)) {
				$_style = ImageStyle::load($style);

				if (!empty($_style)) {
					$imageurl = $_style->buildUrl($uri);
				} else {
					$imageurl = file_create_url($uri);
				}
			} else {
				$imageurl = file_create_url($uri);
			}

		}

		return $imageurl;

	}

	/**
	 * Provides an array of image urls. Use for repeating media entity references.
	 *
	 * @param        $node
	 * @param        $field
	 * @param string $style
	 *
	 * @return array
	 */
	function multi_media_urls($node, $field, $style = '')
	{

		$imageurl = array();

		if (!$node->get($field)->isEmpty()) {
			$images = $node->get($field);

			foreach ($images as $key => $image) {

				$media = Media::load($image->target_id);

				$uri = $media->image->entity->getFileUri();

				if (!empty($style)) {
					$_style = ImageStyle::load($style);

					if (!empty($_style)) {
						$imageurl[] = $_style->buildUrl($uri);
					} else {
						$imageurl[] = file_create_url($uri);
					}
				} else {
					$imageurl[] = file_create_url($uri);
				}
			}
		}

		return $imageurl;

	}

	/**
	 * Generate the URL of an image from a media entity reference.
	 *
	 * @param        $node
	 * @param        $field
	 * @param string $style
	 *
	 * @return array
	 */
	function media_info($node, $field, $style = '')
	{

		$imageurl = '';
		$alt = '';

		if (!$node->get($field)->isEmpty()) {

			$media = Media::load($node->get($field)->target_id);

			$alt = $media->image->alt;
			$uri = $media->image->entity->getFileUri();

			if (!empty($style)) {
				$_style = ImageStyle::load($style);

				if (!empty($_style)) {
					$imageurl = $_style->buildUrl($uri);
				} else {
					$imageurl = file_create_url($uri);
				}
			} else {
				$imageurl = file_create_url($uri);
			}

		}

		return array(
			'url' => $imageurl,
			'alt' => $alt
		);

	}

	/**
	 * Provides an array of image urls. Use for repeating media entity references.
	 *
	 * @param        $node
	 * @param        $field
	 * @param string $style
	 *
	 * @return array
	 */
	function multi_media_info($node, $field, $style = '')
	{

		$images_info = array();

		if (!$node->get($field)->isEmpty()) {
			$images = $node->get($field);

			foreach ($images as $key => $image) {

				$media = Media::load($image->target_id);

				$alt = $media->image->alt;
				$uri = $media->image->entity->getFileUri();

				if (!empty($style)) {
					$_style = ImageStyle::load($style);

					if (!empty($_style)) {
						$imageurl = $_style->buildUrl($uri);
					} else {
						$imageurl = file_create_url($uri);
					}
				} else {
					$imageurl = file_create_url($uri);
				}

				$images_info[] = array(
					'url' => $imageurl,
					'alt' => $alt
				);
			}
		}

		return $images_info;

	}

	/**
	 * Return the url of a simple file field.
	 *
	 * @param $node
	 * @param $field
	 *
	 * @return string
	 */
	function file_url($node, $field)
	{

		$file_url = '';

		if (!$node->get($field)->isEmpty()) {

			$uri = $node->get($field)->entity->getFileUri();

			if (!empty($uri)) {
				$file_url = file_create_url($uri);
			}

		}

		return $file_url;

	}

	/**
	 * Return the url of a file from a Media reference.
	 *
	 * @param $node
	 * @param $field
	 *
	 * @return string
	 */
	function mediafile_url($node, $field)
	{

		if ($node->hasField($field) && !$node->{$field}->isEmpty()) {
			$media = Media::load($node->get($field)->target_id);
			$file = File::load($media->get('field_media_video_file')->target_id);

			return file_create_url($file->get('uri')->value);
		} else {
			return '';
		}

	}

	/**
	 * Return a date field of a node in the user's timezone.
	 *
	 * @param $node
	 * @param $field
	 *
	 * @return string
	 */
	function date_in_default_timezone($node, $field, $format = 'Y-m-d H:i:s')
	{

		$return = '';

		date_default_timezone_set(date_default_timezone_get());

		$date = $node->get($field)->value;

		if (!empty($date)) {
			$date_original = new DrupalDateTime($date, 'UTC');

			if (!empty($date_original)) {
				$return = \Drupal::service('date.formatter')->format($date_original->getTimestamp(), 'custom', $format);
			}
		}

		return $return;

	}

	/**
	 * Return an array of information from a recurring date field. (date_recur module 2.*)
	 *
	 * 'next' has the next occurrence regardless if the date repeats or not.
	 *
	 * @param $date
	 *
	 * @return array
	 */
	function date_recur__dateobj($date)
	{

		// https://www.drupal.org/docs/8/modules/recurring-dates-field/date-recur-field-api

		$return = array();

		if (!empty($date[0]->value)) {

			$helper = $date[0]->getHelper();

			$occ = array();

			$timezone = date_default_timezone_get();

			$now = new \DateTime('now');
			$future = new \DateTime('now');
			$future->modify('+2 year');

			$gen = $helper->generateOccurrences(null, $future);

			foreach ($gen as $occurrence) {
				$occ[] = $occurrence;
			}

			if (!empty($occ)) {
				// $occ has all valid occurrences of a date between now and 2 years in the future

				$return['dates'] = $occ;

				$nowraw = $now->format('U');

				$latest = array();
				foreach ($occ as $o) {

					$start = $o->getStart()->format('U');
					$end = $o->getEnd()->format('U');

					if ($start >= $nowraw || ($start <= $nowraw && $end >= $nowraw)) {
						$latest = $o;
						break;
					}

				}

				$last = end($occ);

				if (!empty($latest)) {

					$return['next'] = array(
						'start' => $latest->getStart()->format('U'),
						'end'   => $latest->getEnd()->format('U')
					);

				} else {

					$return['next'] = array(
						'start' => $last->getStart()->format('U'),
						'end'   => $last->getEnd()->format('U')
					);

				}

				$return['diff'] = $return['next']['end'] - $return['next']['start'];

			}

		}

		return $return;

	}

	/**
	 * Generate a safe slug from any string.
	 *
	 * @param $str
	 *
	 * @return string
	 */
	function gen_slug($str)
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
	function isAssoc(array $arr)
	{
		if (array() === $arr) return false;

		return array_keys($arr) !== range(0, count($arr) - 1);
	}

	// The following helpers should be used when parsing dates because if the formatter service is not used, you may get
	// incorrect time zones

	/**
	 * Helper that parses a date field into a formatted string.
	 *
	 * @param        $date
	 * @param string $format
	 *
	 * @return mixed
	 */
	function parse_date($date, $format = 'Y-m-d H:i:s P')
	{

		$formatted = \Drupal::service('date.formatter')->format(
			$date->getTimestamp(), 'custom', $format
		);

		return $formatted;

	}

	/**
	 * Helper that parses a Unix timestamp into a formatted date string.
	 *
	 * @param        $date
	 * @param string $format
	 *
	 * @return mixed
	 */
	function parse_timestamp($date, $format = 'Y-m-d H:i:s P')
	{
		$formatted = \Drupal::service('date.formatter')->format(
			$date, 'custom', $format
		);

		return $formatted;
	}

	/**
	 * Helper that removes all HTML comments from a string.
	 * Useful for removing template debugging comments from rendered nodes.
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	function remove_html_comments($content = '')
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
	function collapse($input)
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
	function fixYoutube($value, $asUrl = true)
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

	// Menu stuff
	/***
	 * Returns an array representation of a menu tree.
	 *
	 * @param $menu (string)
	 *
	 * @return array.
	 */
	function getMenuArray($menu)
	{

		$output = array();

		$sub_nav = \Drupal::menuTree()->load($menu, new \Drupal\Core\Menu\MenuTreeParameters());
		$manipulators = array(
			array('callable' => 'menu.default_tree_manipulators:checkAccess'),
			array('callable' => 'menu.default_tree_manipulators:generateIndexAndSort'),
		);
		$sub_nav = \Drupal::menuTree()->transform($sub_nav, $manipulators);

		return _generateSubMenuTree($output, $sub_nav);

	}

	/**
	 * Implements _generateSubMenuTree().
	 *
	 * @param $output (string)
	 * @param $input  (array) Menu array
	 * @param $parent (array) Parent menu
	 *
	 * @return array.
	 */
	function _generateSubMenuTree(&$output, &$input, $parent = false)
	{
		$input = array_values($input);
		foreach ($input as $key => $item) {
			//If menu element disabled skip this branch
			if ($item->link->isEnabled()) {
				$name = $item->link->getTitle();
				$url = $item->link->getUrlObject();
				$url_string = $url->toString();

				//If not root element, add as child
				if ($parent === false) {
					$output[$key] = [
						'name'    => $name,
						'tid'     => $key,
						'url_str' => $url_string
					];
				} else {
					$parent = 'submenu-' . $parent;
					$output['child'][$key] = [
						'name'    => $name,
						'tid'     => $key,
						'url_str' => $url_string
					];
				}

				if ($item->hasChildren) {
					if ($item->depth == 1) {
						_generateSubMenuTree($output[$key], $item->subtree, $key);
					} else {
						_generateSubMenuTree($output['child'][$key], $item->subtree, $key);
					}
				}
			}
		}

		return $output;
	}
