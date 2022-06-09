<?php

	namespace Nth\Helpers;

	use Nth\Utils\Tools;
	use \Drupal\paragraphs\Entity\Paragraph;

	class Paragraphs
	{

		/**
		 * Load paragraphs and return a render array of each paragraph as rendered markup
		 *
		 * @param        $instance
		 * @param string $view_mode
		 * @param bool   $foundation_detect_end
		 *
		 * @return array
		 */
		public static function load_paragraphs($instance, $view_mode = 'full', $foundation_detect_end = false)
		{

			$builder = array();

			if (!empty($instance)) {

				foreach ($instance as $key => $_p) {

					$builder[] = self::load_paragraph($_p, $view_mode, $key == (count($instance) - 1), $foundation_detect_end);

				}

			}

			$builder = array_filter($builder);

			if (!empty($builder)) {
				return Tools::ra(implode('', $builder));
			} else {
				return '';
			}

		}

		/**
		 * Load a single paragraph entity from a field instance and return the rendered markup.
		 *
		 * Use:
		 * $_paragraph = $node->get('field_paragraph');
		 * $variables['paragraph'] = load_paragraph($_paragraph);
		 *
		 * If field_paragraph is a repeating list of paragraph entities,
		 * use load_paragraphs instead.
		 *
		 * @param        $instance
		 * @param string $view_mode
		 * @param bool   $last
		 * @param bool   $foundation_detect_end
		 *
		 * @return mixed|null|string
		 */
		public static function load_paragraph($instance, $view_mode = 'full', $last = false, $foundation_detect_end = false)
		{
			$paragraph = self::load_paragraph_data($instance);

			return self::load_rendered_paragraph($paragraph, $view_mode, $last, $foundation_detect_end);
		}

		public static function load_paragraph_from_pid($pid, $view_mode = 'full', $last = false, $foundation_detect_end = false)
		{
			$paragraph = self::load_paragraph_data_from_pid($pid);

			return self::load_rendered_paragraph($paragraph, $view_mode, $last, $foundation_detect_end);
		}

		public static function load_rendered_paragraph($paragraph, $view_mode = 'full', $last = false, $foundation_detect_end = false)
		{
			$view_builder = \Drupal::entityTypeManager()->getViewBuilder('paragraph');
			$the_render = '';

			if (!empty($paragraph)) {

				$build = $view_builder->view($paragraph, $view_mode);

				if (!empty($foundation_detect_end) && !empty($last)) {
					$build['#attributes']['class'][] = 'end';
				}

				$the_render = render($build);
			}

			$render_checker = Tools::remove_html_comments($the_render);

			if (!empty($render_checker)) {
				return $the_render;
			} else {
				return '';
			}
		}

		/**
		 * Load the paragraph entity from the field instance.
		 *
		 * @param $instance
		 *
		 * @return \Drupal\Core\Entity\EntityInterface|null|static
		 */
		public static function load_paragraph_data($instance)
		{

			$target_id = '';
			if (!empty($instance)) {
				$target_id = $instance->get('target_id')->getValue();
			}

			return self::load_paragraph_data_from_pid($target_id);

		}

		/**
		 * Load the paragraph entity from the target id.
		 *
		 * @param $instance
		 *
		 * @return \Drupal\Core\Entity\EntityInterface|null|static
		 */
		public static function load_paragraph_data_from_pid($pid)
		{

			return Paragraph::load($pid);

		}

	}
