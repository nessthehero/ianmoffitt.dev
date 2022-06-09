<?php

	// Link related helper functions

	namespace Nth\Helpers;

	use Nth\Utils\Markup;
	use Nth\Utils\Tools;
	use Drupal\core\template\Attribute;
	use Drupal\core\Url;
	use Nth\Helpers\Files;

  // TODO: Rewrite?

	class Links
	{

		/**
		 * Takes a link field and returns an array of properties for use in templating.
		 *
		 * [
		 *      'valid' => true/false
		 *      'url' => URL of link
		 *      'title' => Title of link
		 *      'attr' => Attributes object of link
		 *      'icon' => Text string of icon to be used in templating
		 * ]
		 *
		 * @param        $link
		 * @param array  $attributes
		 * @param string $icon
		 *
		 * @return array
		 */
		public static function link($link, $attributes = array(), $icon = '', $params = array()) {

			$return = array();

			$return['valid'] = false;

			if (!empty($link->title) && !empty($link->uri)) {

				$return['url'] = self::url($link, $params);

				$url = \Drupal::service('path.validator')->getUrlIfValid($return['url']);

				if (!empty($url)) {

					$return['valid'] = true;

					$return['icon'] = $icon;

					$return['title'] = $link->title;

					if ($url->isExternal()) {
						$return['external'] = true;
						$return['icon'] = 'external';
					}

					$_attr = self::attr($link, $attributes);
					$return['attr'] = $_attr;

				}

			}

			return $return;

		}

		/**
		 * Returns the URL or alias of a link field.
		 *
		 * @param $link
		 *
		 * @return \Drupal\Core\GeneratedUrl|string
		 */
		public static function url($link, $params = array()) {

			$link_url = '';

			if (!empty($link->uri)) {

				$url = Url::fromUri($link->uri);

				if (!empty($url)) {

					if (!empty($params)) {
						$url->setOption('query', $params);
					}

					// This is a hack. There HAS to be a better way to auto-detect the home page.
					$link_url = $url->toString();
					if ($link_url == '/home') { // Replace with the alias of the home page node.
						$link_url = '/';
					}

				}

			}

			return $link_url;

		}

		/**
		 * For parsing a paragraph item that is intended to render as a link.
		 * You will need to add paragraph machine names to the switch statement to support new types.
		 *
		 * By default, supports a paragraph with a single link field, and one with a heading and File field.
		 *
		 * Returns the same data as self::link
		 *
		 * @param $cta
		 *
		 * @return array
		 */
		public static function cta($cta, $params = array())
		{

			$return = array(
				'valid' => false,
				'title' => '',
				'url'   => '',
				'attr'  => new Attribute()
			);

			echo '';

			if (!empty($cta)) {
				$type = $cta->getType();

				switch ($type) {

					case 'file_link':

						$return['title'] = $cta->get('field_heading')->value;
						$return['url'] = Files::mediafile_url($cta, 'field_file', 'field_media_file');

						$return['valid'] = !empty($return['title']) && !empty($return['url']);

						break;

					default:

						echo '';

						$return = self::link($cta->get('field_link'), array(), '', $params);

						break;

				}
			}

			return $return;

		}

		/**
		 * Generate an attributes array for a link. Meant to be fed to l()
		 *
		 * @param       $link
		 * @param array $attributes
		 *
		 * @return Attribute
		 */
		public static function attr($link, $attributes = array())
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

		public static function node($node, $attributes = array(), $icon = '')
		{

			$return = array();

			$return['valid'] = false;

			if (!empty($node) && $node->isPublished()) {

				$return['url'] = Nodes::get_node_link($node->id());

				$url = \Drupal::service('path.validator')->getUrlIfValid($return['url']);

				if (!empty($url)) {

					$return['valid'] = true;

					$return['icon'] = $icon;

					$return['title'] = Nodes::get_node_title($node);

					if ($url->isExternal()) {
						$return['external'] = true;
						$return['icon'] = 'external';
					}

					$_attr = self::attr(array(), $attributes);
					$return['attr'] = $_attr;

				}

			}

			return $return;

		}

	}
