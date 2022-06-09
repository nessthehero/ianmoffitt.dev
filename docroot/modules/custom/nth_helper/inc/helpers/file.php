<?php


	namespace Nth\Helpers;

	use Drupal\file\Entity\File;
	use Drupal\media\Entity\Media;

  // TODO: Rewrite?

	class Files {

		/**
		 * Return the url of a simple file field.
		 *
		 * @param $node
		 * @param $field
		 *
		 * @return string
		 */
		public static function file_url($node, $field)
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
		public static function mediafile_url($node, $field, $mediafield = 'field_media_video_file')
		{

			if ($node->hasField($field) && !$node->{$field}->isEmpty()) {
				$media = Media::load($node->get($field)->target_id);
				if (!empty($media)) {
					$file = File::load($media->get($mediafield)->target_id);

					return file_create_url($file->get('uri')->value);
				} else {
					return '';
				}
			} else {
				return '';
			}

		}

	}

