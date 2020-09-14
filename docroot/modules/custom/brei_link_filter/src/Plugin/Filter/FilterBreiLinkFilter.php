<?php

	namespace Drupal\brei_link_filter\Plugin\Filter;

	use Drupal\filter\FilterProcessResult;
	use Drupal\filter\Plugin\FilterBase;

	/**
	 * @Filter(
	 *   id = "filter_brei_link_filter",
	 *   title = @Translation("Add rel='noopener nofollow' to external links."),
	 *   description = @Translation("BarkleyREI Link Filter helper."),
	 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE,
	 * )
	 */
	class FilterBreiLinkFilter extends FilterBase
	{

		public function process($text, $langcode)
		{

			$needles = array();

			if (!empty($text)) {

				$doc = new \DOMDocument();
				@$doc->loadHTML($text);
				$links = $doc->getElementsByTagName('a');

				if (!empty($links)) {
					foreach ($links as $link) {
						$newlink = $link->cloneNode(true);

						$_target = $link->getAttribute('target');
						$_rel = $link->getAttribute('rel');

						if ($_target == '_blank' && empty($_rel)) {
							$newlink->setAttribute('rel', 'noopener nofollow');
						}

						$needles[] = array(
							'original'    => $this->renderElement($link),
							'replacement' => $this->renderElement($newlink)
						);
					}
				}

				if (!empty($needles)) {
					foreach ($needles as $needle) {
						$text = str_replace($needle['original'], $needle['replacement'], $text);
					}
				}

			}

			return new FilterProcessResult($text);
		}

		private function renderElement($content)
		{
			if (!empty($content)) {
				$newDom = new \DOMDocument();
				$_newNode = $newDom->importNode($content, true);
				$newDom->appendChild($_newNode);

				return str_replace("\n", "", $newDom->saveHTML());
			}

			return '';
		}

	}
