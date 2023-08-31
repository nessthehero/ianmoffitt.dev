<?php

	namespace Drupal\nth_helper;

	use Drupal\Core\Url;
	use Twig\Extension\AbstractExtension;
	use Twig\TwigFunction;

	class TwigExtension extends AbstractExtension {

		CONST PREFIX = 'ian-icon';

		/**
		 * {@inheritdoc}
		 */
		public function getName() {
			return 'nth_helper.twig';
		}

		/**
		 * {@inheritdoc}
		 */
		public function getFunctions() {
			return [
				new TwigFunction('svg', [$this, 'svg']),
			];
		}

		/**
		 * Builds a render array for SVGs.
		 *
		 * @param string $icon
		 *   Name of the icon to use.
		 *
		 * @return array
		 *   Render array for the SVG.
		 */
		public static function svg($icon) {
			return [
				'#type' => 'html_tag',
				'#tag' => 'svg',
				'#attributes' => [
					'class' => [
						static::PREFIX . ' ' . static::PREFIX . '-' . $icon
					],
					'focusable' => 'false'
				],
				'content' => [
					'use' => [
						'#type' => 'html_tag',
						'#tag' => 'use',
						'#attributes' => [
							'xlink:href' => Url::fromUserInput("#" . static::PREFIX . "-$icon")
								->toString(),
						],
					]
				]
			];
		}

	}