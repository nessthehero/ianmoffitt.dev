<?php

	namespace Nth\Helpers;

	use Nth\Utils\Tools;
	use Drupal\image\Entity\ImageStyle;
	use Drupal\media\Entity\Media;

	class Images {

		/**
		 * Generate a render array of an image from an image field
		 *
		 * @param        $node
		 * @param        $field
		 * @param string $style
		 *
		 * @return array
		 */
		public static function image($node, $field, $style = '')
		{

			$img = $node->get($field);
			$builder = '';

			$alt = '';
			if (!empty($img->alt)) {
				$alt = $img->alt;
			};

			$imagestyleurl = self::image_url($node, $field, $style);

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
		public static function image_url($node, $field, $style = '')
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
		public static function multi_image_urls($node, $field, $style = '')
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

		public static function multi_image_style_urls($node, $field, $style = array())
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
		public static function media_url($node, $field, $style = '')
		{

			$imageurl = '';

			if (!$node->get($field)->isEmpty()) {

				$media = Media::load($node->get($field)->target_id);
				$image = $media->get('field_media_image');

				$alt = $image->alt;
				$uri = $image->entity->getFileUri();

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
		public static function multi_media_urls($node, $field, $style = '')
		{

			$imageurl = array();

			if (!$node->get($field)->isEmpty()) {
				$images = $node->get($field);

				foreach ($images as $key => $image) {

					$media = Media::load($image->target_id);
					$image = $media->get('field_media_image');

					$alt = $image->alt;
					$uri = $image->entity->getFileUri();

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
		public static function media_info($node, $field, $style = '', $srcfield = 'url')
		{

			$imageurl = '';
			$alt = '';

			if (!$node->get($field)->isEmpty()) {

				$media = Media::load($node->get($field)->target_id);
				$image = $media->get('field_media_image');

				$alt = $image->alt;
				$uri = $image->entity->getFileUri();

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
				$srcfield => $imageurl,
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
		public static function multi_media_info($node, $field, $style = '')
		{

			$images_info = array();

			if (!$node->get($field)->isEmpty()) {
				$images = $node->get($field);

				foreach ($images as $key => $image) {

					$media = Media::load($image->target_id);
					$image = $media->get('field_media_image');

					$alt = $image->alt;
					$uri = $image->entity->getFileUri();

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

		public static function media_entity_info($media, $style = '') {

			$imageurl = '';
			$alt = '';

			if (!empty($media)) {

				$alt = $media->get('field_media_image')->alt;
				$uri = $media->get('field_media_image')->entity->getFileUri();

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

	}
