<?php

	use Drupal\image\Entity\ImageStyle;
	use Drupal\core\template\Attribute;
	use Drupal\core\Url;
	use Drupal\core\Link;
	use Drupal\taxonomy\Entity\Vocabulary;
	use Drupal\taxonomy\Entity\Term;

	include_once('video.php');
	include_once('news.php');
	include_once('feature.php');
	include_once('content.php');
	// include_once('fields.php');
	include_once('paragraphs.php');
	include_once('nodes.php');

	// Finders
	include_once('finder.php');
//    include_once('events.php');
	// include_once('directory.php');
	// include_once('projects.php');
	// include_once('programs.php');
	// include_once('degrees.php');

	/**
	 * Returns key of array, or default value given. Mostly used to get query strings.
	 *
	 * @param  array  $q       Array, usually given drupal_get_query_parameters()
	 * @param  string $i       key of array
	 * @param  string $default Fallback value
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
	 * @param  string $elem  needle
	 * @param  array  $array multidimensional haystack
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
	 * Generate the URL of an image
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

	/***
	 * Generate an anchor tag from a link field
	 *
	 * @param        $link
	 * @param string $icon
	 * @param array  $attributes
	 * @param string $after
	 *
	 * @return string
	 */
	function l($link, $icon = '', $attributes = array(), $after = '')
	{

		$output = '';

		if (!empty($link->title) && !empty($link->uri)) {

			$attr = array();
			$_attr = '';
			$svg = '';

			if (!empty($link->options['attributes'])) {

				if (!empty($attributes)) {
					$attr = array_merge($attr, $attributes, $link->options['attributes']);
				} else {
					$attr = array_merge($attr, $link->options['attributes']);
				}

			} else {
				$attr = array_merge($attr, $attributes);
			}

			$_attr = new Attribute($attr);

			if (!empty($icon)) {
				$svg = svg($icon);
			}

			$link_url = l_url($link);

			$output = sprintf('<a href="%s" %s>%s %s</a>%s',
				$link_url,
				$_attr,
				$svg,
				$link->title,
				$after
			);

		}

		return $output;

	}

	function l_url($link)
	{

		$link_url = '';

		$url = Url::fromUri($link->uri);

		// This is a hack. There HAS to be a better way to auto-detect the home page.
		$link_url = $url->toString();
		if ($link_url == '/home-page') {
			$link_url = '/';
		}

		return $link_url;

	}

	/**
	 * Generate an array of anchor tags from a multiple link field
	 *
	 * @param        $links
	 * @param string $icons
	 * @param array  $attributes
	 *
	 * @return array
	 */
	function multi_l($links, $icons = '', $attributes = array(), $after = '')
	{

		$output = array();

		foreach ($links as $key => $l) {

			$output[] = l($l, $icons, $attributes, $after);

		}

		return $output;

	}

	/**
	 * Build an anchor tag with values and attributes
	 *
	 * @param        $text
	 * @param        $uri
	 * @param string $icon
	 * @param array  $attributes
	 *
	 * @return string anchor tag
	 */
	function lm($text, $uri, $icon = '', $attributes = array())
	{

		$output = '';
		$svg = '';

		if (!empty($text) && !empty($uri)) {

			$_attr = new Attribute($attributes);

			if (!empty($icon)) {
				$svg = svg($icon);
			}

			$output = sprintf('<a href="%s" %s>%s %s</a>',
				$uri,
				$_attr,
				$svg,
				$text
			);

		}

		return $output;

	}

	function gen_slug($str)
	{
		# special accents
		$a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'Ð', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', '?', '?', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', '?', '?', 'L', 'l', 'N', 'n', 'N', 'n', 'N', 'n', '?', 'O', 'o', 'O', 'o', 'O', 'o', 'Œ', 'œ', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'Š', 'š', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Ÿ', 'Z', 'z', 'Z', 'z', 'Ž', 'ž', '?', 'ƒ', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', '?', '?', '?', '?', '?', '?');
		$b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');

		return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), str_replace($a, $b, $str)));
	}

	function getTagsArray($field)
	{

		$t = array();
		$tmp = array();

		$tmp = $field->referencedEntities();

		foreach ($tmp as $tt) {

			$t[] = array(
				'tid' => $tt->id(),
				'vid' => $tt->getVocabularyId(),
				'name' => $tt->getName()
			);

		}

		return $t;

	}

	function isAssoc(array $arr)
	{
		if (array() === $arr) return false;

		return array_keys($arr) !== range(0, count($arr) - 1);
	}

	function parse_date($date, $format = 'Y-m-d H:i:s P')
	{

		$formatted = \Drupal::service('date.formatter')->format(
			$date->getTimestamp(), 'custom', $format
		);

		return $formatted;

	}

	function remove_html_comments($content = '')
	{
		return trim(preg_replace('/<!--(.|\s)*?-->/', '', $content));
	}

	function getTagsArrayFromVocabulary($vocabulary, $checked = array())
	{

		$return = array();

		$vids = Vocabulary::loadMultiple();
		foreach ($vids as $vid) {
			if ($vid->id() == $vocabulary) {
				$container = \Drupal::getContainer();
				$terms = $container->get('entity.manager')->getStorage('taxonomy_term')->loadTree($vid->id());
				if (!empty($terms)) {
					foreach ($terms as $term) {

						$active = false;
						if (!empty(in_array($term->tid, $checked))) {
							$active = true;
						}

						$return['labels'][] = $term->name;
						$return['tids'][] = $term->tid;
						$return['associated'][] = array(
							'label'  => $term->name,
							'safe'   => gen_slug($term->name),
							'tid'    => $term->tid,
							'active' => $active
						);
					}
				}
				break;
			}
		}

		return $return;

	}

	function getTermInfo($tid)
	{

		$return = array();

		if (!empty($tid)) {

			$id = $tid;
			$name = Term::load($tid)->get('name')->value;

			$return = array(
				'id'   => $id,
				'name' => $name
			);

		}

		return $return;

	}

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
