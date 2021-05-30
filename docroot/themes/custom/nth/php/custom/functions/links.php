<?php

	// Link related helper functions

	use Drupal\core\template\Attribute;
	use Drupal\core\Url;

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
	function l($link, $icon = '', $attributes = array(), $after = '', $wrap = '')
	{

		$output = '';

		if (!empty($link->title) && !empty($link->uri)) {

			$_attr = l_attr($link, $attributes);

			$svg = '';
			if (!empty($icon)) {
				$svg = svg($icon);
			}

			$link_url = l_url($link);

			$the_title = $link->title;

			if (!empty($wrap)) {
				$the_title = '<span class="' . $wrap . '">' . $the_title . '</span>';
			}

			$output = sprintf('<a href="%s" %s>%s %s</a>%s',
				$link_url,
				$_attr,
				$svg,
				$the_title,
				$after
			);

		}

		return $output;

	}

	/**
	 * Generate an attributes array for a link. Meant to be fed to l()
	 *
	 * @param       $link
	 * @param array $attributes
	 *
	 * @return Attribute
	 */
	function l_attr($link, $attributes = array())
	{

		$attr = array();

		if (!empty($link->options['attributes'])) {

			if (!empty($attributes)) {
				$attr = array_merge($attr, $attributes, $link->options['attributes']);
			} else {
				$attr = array_merge($attr, $link->options['attributes']);
			}

		} else {
			$attr = array_merge($attr, $attributes);
		}

		// Accessibility recommendation
		// Add a notice after the Title that notifies the user this opens in a new window.
		// Also patch outgoing link vulnerability.
		$warning = '(This link opens a new tab)';
		if (!empty($attr['target']) && $attr['target'] != '_self') {
			if (!empty($attr['title'])) {
				$attr['title'] = $attr['title'] . ' ' . $warning;
			} else {
				$attr['title'] = $warning;
			}
			$attr['rel'] = 'noopener noreferrer';
		}

		$_attr = new Attribute($attr);

		return $_attr;

	}

	/**
	 * Generate the fully qualified url of a link field.
	 *
	 * @param $link
	 *
	 * @return \Drupal\Core\GeneratedUrl|string
	 */
	function l_url($link)
	{

		$link_url = '';

		$url = Url::fromUri($link->uri);

		if (!empty($url)) {

			// This is a hack. There HAS to be a better way to auto-detect the home page.
			$link_url = $url->toString();
			if ($link_url == '/home') { // Replace with the alias of the home page node.
				$link_url = '/';
			}

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
	function lm($text, $uri, $icon = '', $attributes = array(), $wrapicon = false)
	{

		$output = '';
		$svg = '';

		if (!empty($text) && !empty($uri)) {

			$_attr = new Attribute($attributes);

			if (!empty($icon)) {
				if (!empty($wrapicon)) {
					$svg = svgc($icon);
				} else {
					$svg = svg($icon);
				}
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
