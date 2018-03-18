<?php

	use Drupal\image\Entity\ImageStyle;
	use Drupal\core\template\Attribute;

    include_once('video.php');
    include_once('news.php');
	include_once('feature.php');
	include_once('content.php');
	// include_once('fields.php');
	include_once('paragraphs.php');
	include_once('nodes.php');

	// Finders
    include_once('events.php');
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
function qs($q = array(), $i = '', $default = '') {

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

    for ($i = 0; $i < $count - 1; $i++)
    {
        $arg = func_get_arg($i);

        if (!isset($arg))
        {
            continue;
        }

        if (is_array($arg))
        {

            $key = func_get_arg($i + 1);

            if (is_null($key) || is_string($key) || is_int($key) || is_float($key) || is_bool($key))
            {

                if (isset($arg[$key]))
                {
                    // print_r($arg);
                    return $arg[$key];
                }

                $i++;
                continue;
            }
        }

        return $arg;
    }

    if ($i < $count)
    {
        return func_get_arg($i);
    }

    return null;
}

/**
 * Find a value in a multidimensional array
 * @param  string $elem needle
 * @param  array $array multidimensional haystack
 * @return bool         boolean if value was found or not
 */
function in_multiarray($elem, $array) {
    foreach ($array as $key => $value) {
        if ($value==$elem){
            return true;
        }
        elseif(is_array($value)){
            if($this->in_multiarray($elem, $value))
                    return true;
        }
    }

    return false;
}

function ra($content) {

	// If you use #markup, it will filter out bad tags, but #children does not
	return array(
		'#children' => $content
	);
}

function image($node, $field, $style = '') {

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

	return ra( $builder );

}

function image_url($node, $field, $style = '') {

    $imageurl = '';

    if (!$node->get($field)->isEmpty()) {

        $uri = $node->get($field)->entity->getFileUri();

        if (!empty($style)) {

            $imageurl = ImageStyle::load($style)->buildUrl($uri);

        } else {

            $imageurl = file_create_url($uri);

        }

    }

	return $imageurl;

}

function l($link, $icon = '', $attributes = array()) {

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

		$output = sprintf('<a href="%s" %s>%s %s</a>',
			$link->uri,
			$_attr,
			$svg,
			$link->title
		);

	}

	return $output;

}

function multi_l($links, $icons = '', $attributes = array()) {

	$output = array();

	foreach ($links as $key => $l) {

		$output[] = l($l, $icons, $attributes);

	}

	return $output;

}

function gen_slug($str){
    # special accents
    $a = array('À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','Ð','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','?','?','J','j','K','k','L','l','L','l','L','l','?','?','L','l','N','n','N','n','N','n','?','O','o','O','o','O','o','Œ','œ','R','r','R','r','R','r','S','s','S','s','S','s','Š','š','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Ÿ','Z','z','Z','z','Ž','ž','?','ƒ','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','?','?','?','?','?','?');
    $b = array('A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','s','a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','D','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','IJ','ij','J','j','K','k','L','l','L','l','L','l','L','l','l','l','N','n','N','n','N','n','n','O','o','O','o','O','o','OE','oe','R','r','R','r','R','r','S','s','S','s','S','s','S','s','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Y','Z','z','Z','z','Z','z','s','f','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','A','a','AE','ae','O','o');
    return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/','/[ -]+/','/^-|-$/'),array('','-',''),str_replace($a,$b,$str)));
}

function getTagsArray($field) {


	$t = array();

	foreach ($field as $key => $f) {
		$t[] = $f->target_id;
	}

	return $t;

}

function isAssoc(array $arr)
{
    if (array() === $arr) return false;
    return array_keys($arr) !== range(0, count($arr) - 1);
}

function parse_date($date, $format = 'Y-m-d H:i:s P') {

	$formatted = \Drupal::service('date.formatter')->format(
		$date->getTimestamp(), 'custom', $format
	);

	return $formatted;

}
