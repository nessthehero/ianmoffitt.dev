<?php

	use Drupal\Core\Url;
	use Drupal\Core\Link;
	use Drupal\node\Entity\Node;
	use Symfony\Component\HttpFoundation\RedirectResponse;

	/**
	 * Global preprocessor - run for everything
	 *
	 * @param $variables
	 * @param $hook
	 */
	function ian_preprocess(&$variables, $hook)
	{

//		build_includes($variables);

		// Sometimes the preprocess_html hook doesn't work correctly,
		// So we do stuff here instead.

		echo '';

		if ($hook == 'html') {

			$node = Drupal::request()->attributes->get('node');
			$revision =  Drupal::request()->attributes->get('node_revision');
			$preview = Drupal::request()->attributes->get('node_preview');

			if (empty($node)) {
				if (!empty($preview)) {
					$node = $preview;
				}
			}

//			$variables['partial_svg'] = contents('svg');
//			$variables['partial_access'] = contents('accessnav');

			if (!empty($node)) {

				if (gettype($node) == 'string') {
					if ($revision && $revision != '') {
						$node = Drupal::entityTypeManager()->getStorage('node')->loadRevision($revision);
					} else {
						$node = Node::load($node);
					}
				}

				$type = $node->getType();

				switch ($type) {

					default:

//						$variables['#attached']['library'][] = 'ian/level';

//						$variables['attributes']['class'][] = "level";

						break;

				}

			} else {

//				$variables['attributes']['class'][] = "level";

			}

		}

		if ($hook === 'user') {

			$account = $variables['elements']['#user'];

			$variables['username'] = $account->getDisplayName();

		}

		if ($hook === 'block') {



		}

	}

	function ian_preprocess_field(&$variables) {

		$bundle = $variables['element']['#bundle'];

		switch ($bundle) {

			case 'accordion':

				$variables['attributes']['data-allow-all-closed'] = 'true';

				break;

			default:
				break;

		}

	}

	/**
	 * Page Title preprocessor. Preprocesses the page title block, not the region.
	 *
	 * @param $variables
	 */
	function ian_preprocess_page_title(&$variables)
	{

		// Get node from request
		if ($node = \Drupal::request()->attributes->get('node')) {

			if (gettype($node) == 'string') {
				$node = Node::load($node);
			}

			// Change the title to the heading field if it exists and is not empty.
			if ($node->hasField('field_heading') && !$node->field_heading->isEmpty()) {
				$variables['title'] = $node->get('field_heading')->value;
			}

		}

	}

	/**
	 * Breadcrumb preprocessor
	 *
	 * @param $variables
	 */
	function ian_preprocess_breadcrumb(&$variables)
	{

		// The breadcrumbs are cached, and this can cause breadcrumbs to "stick" across pages, so we provide a
		// context to the caching system to cache the breadcrumbs based on a unique URL. This guarantees that the
		// breadcrumbs will be unique and correct on every page.
		$variables['#cache']['contexts'][] = 'url';

		// If there are pages in the breadcrumb trail...
		if (!empty($variables['breadcrumb'])) {

			$current_page = '';

			// If the page has the heading field, we'll use that for the breadcrumb title, but fall back to the node title.
			if ($node = \Drupal::request()->attributes->get('node')) {

				// Sometimes the request returns the node id instead of the object, so get the object if that happens.
				if (gettype($node) == 'string') {
					$node = Node::load($node);
				}

				// Set the breadcrumb title to the heading field if it exists and is not empty.
				$heading = $node->get('title')->value;

				$current_page = $heading;

				$type = $node->getType();

			}

			// Generate url to the home page
			$home_url = Url::fromUri('internal:/', array(
				'attributes' => array(
					'title' => 'Home Page',
					'class' => array('breadcrumbs__link')
				)
			));

			$home_link = Link::fromTextAndUrl(ra("<span class='show-for-sr'>Home Page</span>" . svg('home')), $home_url);

			$variables['breadcrumb'][0] = $home_link;

			foreach ($variables['breadcrumb'] as $key => $crumb) {

				if ($key != 0) {
					$link = Link::fromTextAndUrl($crumb['text'], Url::fromUserInput($crumb['url'], array(
						'attributes' => array(
							'class' => array('breadcrumbs__link')
						)
					)));

					$variables['breadcrumb'][$key] = $link;
				}

			}

			$variables['current'] = $current_page;

		}

	}

	/**
	 * Change theme suggestions for specific render hooks
	 * This is done especially for instances where the markup changes substantially for different types of content
	 * or if AJAX data needs requested from a page via a query string
	 *
	 * @param $suggestions
	 * @param $variables
	 * @param $hook
	 */
	function ian_theme_suggestions_alter(&$suggestions, $variables, $hook)
	{

		// Grab all query strings
		$_q = \Drupal::request()->query->all();

		$data_mode = false;
		if (!empty($_q['data'])) {
			$data_mode = true;
		}

		// Detect hook to change theme suggestions
		switch ($hook) {

			case 'menu':

				if (isset($variables["attributes"]["region"])) {
					$suggestions[] = "menu__" . $variables["attributes"]["region"];
				}

				break;

			// HTML layer
			case 'html':

				// Get node object from request
				$node = Drupal::request()->attributes->get('node');
				$revision = Drupal::request()->attributes->get('node_revision');
				$preview = Drupal::request()->attributes->get('node_preview');

				if (empty($node)) {
					if (!empty($preview)) {
						$node = $preview;
					}
				}

				if (gettype($node) == 'string') {
					if ($revision && $revision != '') {
						$node = Drupal::entityTypeManager()->getStorage('node')->loadRevision($revision);
					} else {
						$node = Node::load($node);
					}
				}

				if (!empty($node)) {

					$type = $node->getType();

					switch ($type) {

						case 'home':

							break;

						default:

							if ($data_mode) {
								$suggestions[] = "html__data";
							}

							break;
					}

				}

				break;

			case 'off_canvas_page_wrapper':

				if (!empty($data_mode)) {
					$suggestions[] = "off_canvas_page_wrapper__data";
				}

				break;

			case 'settings_tray_page_wrapper':

				if (!empty($data_mode)) {
					$suggestions[] = "settings_tray_page_wrapper__data";
				}

				break;

			// Pages do not automatically get a template based on the content type, but we often need to specify this,
			// so most content types may appear here.
			case 'page':

				if (!is_null(Drupal::requestStack()->getCurrentRequest()->attributes->get('exception'))) {
					$status_code = Drupal::requestStack()->getCurrentRequest()->attributes->get('exception')->getStatusCode();
					switch ($status_code) {
						case 403:
						case 404:
							$suggestions[] = 'page__' . (string) $status_code;
							break;

						default:
							break;
					}
				}

				// Get node object from request
				$node = Drupal::request()->attributes->get('node');
				$revision =  Drupal::request()->attributes->get('node_revision');
				$preview = Drupal::request()->attributes->get('node_preview');

				if (empty($node)) {
					if (!empty($preview)) {
						$node = $preview;
					}
				}

				if (!empty($node)) {

					if (gettype($node) == 'string') {
						if ($revision && $revision != '') {
							$node = Drupal::entityTypeManager()->getStorage('node')->loadRevision($revision);
						} else {
							$node = Node::load($node);
						}
					}

					$type = $node->getType();

					switch ($type) {

						case 'home':

							$suggestions[] = "page__home";

							break;

						case 'webform':

							$suggestions[] = "page__webform";

							break;

						default:

							break;

					}

				}

				break;

			case 'node':

				// Get the node from the variables. We don't want the one from the request because that doesn't change and
				// there might be more than one node on the page we're rendering.
				$node = $variables['elements']['#node'];

				if (!empty($node)) {

					$type = $node->getType();

					$mode = $variables['elements']['#view_mode'];

					switch ($type) {

						default:

							break;

					}

				}

				break;

			default:
				break;

		}

	}
