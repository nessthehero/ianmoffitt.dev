<?php

	// Code that generates raw content and/or markup

	/**
	 * Renders an svg icon with appropriate markup
	 *
	 * @param  string  $icon        slug of icon
	 * @param  boolean $screen      also render screenreader text (optional)
	 * @param  string  $screen_text screenreader text to render (optional)
	 *
	 * @return string               markup to render
	 */
	function svgicon($icon, $screen = false, $screen_text = '')
	{

		$r = "";

		if (is_string($icon)) {

			if ($screen && is_string($screen_text)) {
				$r .= "<span class='show-for-sr'>" . $screen_text . "</span>";
			}

			$r .= svg($icon);

		}

		return $r;

	}

	/**
	 * Returns markup for a single svg icon.
	 *
	 * @param  string $icon icon name
	 *
	 * @return string        icon markup
	 */
	function svg($icon)
	{

		$r = "";

		if (is_string($icon)) {
			$r = "<svg class='brei-icon brei-icon-" . $icon . "'><use xlink:href='#brei-icon-" . $icon . "'></use></svg>";
		}

		return $r;

	}

	/***
	 * Returns markup for a single svg icon in a containing span.
	 *
	 * @param        $icon
	 * @param string $class
	 *
	 * @return string
	 */
	function svgc($icon, $class = 'svg-container')
	{

		$r = "";

		if (is_string($icon)) {
			$r = "<span class='" . $class . "' aria-hidden='true'>" . svg($icon) . "</span>";
		}

		return $r;
	}

	function sanitize_svg($icon)
	{

		$icon = str_replace('<svg ', '<svg class="brei-icon" ', $icon);
		$icon = preg_replace('/width="\d+" /', '', $icon);
		$icon = preg_replace('/height="\d+" /', '', $icon);

		return $icon;

	}

	/**
	 * Grabs a partial from the partials directory and returns the markup
	 *
	 * @param $filename
	 *
	 * @return bool|string
	 */
	function contents($filename)
	{

		return file_get_contents(dirname(__DIR__) . '/partials/' . $filename . '.php');

	}

	/**
	 * Includes partial from php/partials
	 *
	 * @param  string $filename file name sans php
	 *
	 * @return void
	 */
	function partial($filename)
	{

		global $base_url;
		global $theme;

		$theme_path = \Drupal::service('extension.path.resolver')->getPath('theme', $theme);

		include(dirname(__DIR__) . '/partials/' . $filename . '.php');

	}

	/**
	 * Filter letters for paginated results. Might not be necessary.
	 * @param $node
	 * @param $q
	 * @param $let
	 *
	 * @return array
	 */
	function letters($node, $q, $let)
	{

		$container = "<ul class=\"jump-list clearfix\"><li class=\"label\">Jump To:</li>%s%s</ul>";
		$letters = array();

		$letter = $q['letter'];
		$q['letter'] = '';
		$alias = $node->toUrl()->toString() . '?' . http_build_query($q);

		$reset = sprintf("<li><a href=\"%s\" class=\"reset\" id=\"reset\">All</a></li>", $alias);

		unset($q['letter']);
		$q['page'] = 1;

		foreach ($let as $key => $l) {

			$q['letter'] = $key;

			$alias = $node->toUrl()->toString() . '?' . http_build_query($q);

			$class = "";
			if ($l === 0) {
				$class .= " disabled";
				$alias = "#";
			}
			if (strtoupper($key) == strtoupper($letter)) {
				$class .= " active";
			}

			$letters[] = sprintf("<li><a href=\"%s\" id=\"%s\" class=\"%s\">%s</a></li>",
				$alias,
				strtoupper($key),
				$class,
				strtoupper($key)
			);
		}

		return ra(sprintf($container,
			$reset,
			implode("", $letters)
		));

	}

	/**
	 * Generate paging for indexes of content
	 *
	 * @param      $node       - Node object
	 * @param      $q          - Array of query string values
	 * @param      $current    - Current Page (0 based)
	 * @param      $page_count - Page Count
	 * @param bool $ellipsis   - Show ellipsis between page numbers (to reduce width) (only for more than 9 pages)
	 *
	 * @return array|string
	 */
	function pagination($node, $q, $current, $page_count, $ellipsis = true)
	{

		// Build the url for the next page
		$q['page'] = $current + 1;

		$alias = $node->toUrl()->toString();

		$next_page = $alias . '?' . http_build_query($q);
		$next_page_number = $current + 1;

		// Build the url for the previous page
		$q['page'] = $current - 1;
		$prev_page_number = $current - 1;
		$prev_page = $alias . '?' . http_build_query($q);

		// Restore the current page to the query object
		$q['page'] = $current;

		// Build out array of possible page numbers
		$n = range(1, $page_count);
		foreach ($n as $k) {
			// safe => page number can render
			// number => real page number
			// ellipsis => render an ellipsis instead of a page number
			// current => this is the current page
			$pages_array[] = array(
				'safe'     => false,
				'number'   => $k,
				'ellipsis' => 'no',
				'current'  => 'no'
			);
		}

		// Mark the current page as safe and current
		if (!empty($pages_array[$current - 1])) {
			$pages_array[$current - 1]['safe'] = true;
			$pages_array[$current - 1]['current'] = 'yes';
		}

		// If the pages array is greater than 9 and ellipsis is true, we'll smartly detect what page numbers we can show
		if ($page_count > 9 && !empty($ellipsis)) {

			// Always show first page and second page
			$pages_array[0]['safe'] = true;
			$pages_array[1]['safe'] = true;

			// Always show last two pages
			$pages_array[count($pages_array) - 1]['safe'] = true;
			$pages_array[count($pages_array) - 2]['safe'] = true;

			// Page before current and page after current will be shown
			if (!empty($pages_array[$current - 2])) {
				$pages_array[$current - 2]['safe'] = true;
			} // prev page
			if (!empty($pages_array[$current])) {
				$pages_array[$current]['safe'] = true;
			} // next page

			// Determine the midpoint
			$midpoint = ceil($page_count / 2);

			// determine number of sections
			//
			// A "section" is a split between two safe pages. We need to count them to know where to put ellipses.
			$sections = 0;
			$flip = 0;

			foreach ($pages_array as $key => $j) {
				if ($j['safe'] == $flip) {
					$sections += 1;
					$flip = !$flip;
				}
			}

			// We subtract one to get a number that makes more sense.
			$sections -= 1;

			// There are only two possible outcomes for sections. 1 or 3. If it is 1, then we know we're not on a page that could be
			// considered near the "middle" of the total number of pages, and we'll add the midpoint pages.
			if ($sections == 1) {
				$pages_array[$midpoint]['safe'] = true;
				$pages_array[$midpoint - 1]['safe'] = true;
				$pages_array[$midpoint + 1]['safe'] = true;
			}

			// Here we'll figure out where to put the ellipses
			foreach ($pages_array as $key => $j) {

				// If we're on a page that's safe to render..
				if ($j['safe'] == 1) {

					// And the next page exists...
					if (!empty($pages_array[$key + 1])) {

						// And it isn't safe to render...
						if ($pages_array[$key + 1]['safe'] == 0) {

							// We'll render an ellipsis instead
							$pages_array[$key + 1]['ellipsis'] = 'yes';
						}
					}
				}

				// If we're on a page that is not safe to render...
				if ($j['safe'] == 0) {

					// And the previous page exists...
					if (!empty($pages_array[$key - 1])) {

						// And that page is safe...
						if ($pages_array[$key - 1]['safe'] == 1) {

							// We'll render an ellipsis here instead
							$pages_array[$key]['ellipsis'] = 'yes';
						}
					}
				}
			}

			// A lot of the above logic may target the same pages, but this is easier than just looping through the pages themselves and figuring
			// out if they need printed, because we don't have to smartly detect anything. We just label all the pages we know are safe, figure out
			// if we need any ellipses anywhere, and then when we render later, we don't have to worry about anything except obeying the flags we already set

		} else {

			// We either have less than 9 pages or we don't care about the ellipsis.
			// Show every page
			foreach ($pages_array as $key => $j) {
				$pages_array[$key]['safe'] = true;
				$pages_array[$key]['ellipsis'] = 'no';
			}

		}

		// Gotta have more than one page to show the pagination.
		if ($page_count > 1) {

			$container = "";
			$previous = "";
			$nextious = ""; // Stop laughing
			$pages_to_print = array();

			// Disable previous button if we're on page 1
			if ($current == 1) {
				$previous = sprintf("<li class='pagination__item pagination__item--prev'><a href='#' class='pagination__link pagination__link--disabled' aria-label='Go to the previous page'>%s</a></li>",
					'<span class="show-for-sr">Go to the previous page</span>' .
					'<span class="pagination__icon pagination__icon--default">' . svg('chevron') . '</span>' .
					'<span class="pagination__icon pagination__icon--disabled">' . svg('chevron') . '</span>'
				);
			} else {
				$previous = sprintf("<li class='pagination__item pagination__item--prev'><a href='%s' data-page='%s' class='pagination__link' aria-label='Go to the previous page'>%s</a></li>",
					$prev_page,
					$prev_page_number,
					'<span class="show-for-sr">Go to the previous page</span>' .
					'<span class="pagination__icon pagination__icon--default">' . svg('chevron') . '</span>' .
					'<span class="pagination__icon pagination__icon--disabled">' . svg('chevron') . '</span>'
				);
			}

			// Start looping through page numbers
			foreach ($pages_array as $key => $page) {

				// We're overriding the query page object so we can build URLs
				$q['page'] = $page['number'];

				if (!empty($page['safe']) || $page['ellipsis'] == 'yes') {

					// If we're on a safe page...
					if (!empty($page['safe'])) {

						// And it's the current page, we'll render a span...
						if ($page['current'] == 'yes') {

							$pages_to_print[] = sprintf("<li class=\"pagination__item pagination__item--active\"><a href='#' class=\"pagination__link\"><span class=\"show-for-sr\">You're on page</span> %s</a></li>",
								$page['number']
							);

						} else {

							// Otherwise we'll render a link to that page
							$pages_to_print[] = sprintf("<li class=\"pagination__item\"><a href='%s' class=\"pagination__link\" data-page='%s' aria-label='%s'>%s</a></li>",
								$alias . '?' . http_build_query($q),
								$page['number'],
								"Page " . $page['number'],
								$page['number']
							);

						}

					}

					// If it's an ellipsis, we'll render that.
					if ($page['ellipsis'] == 'yes') {

						$pages_to_print[] = "<li class=\"pagination__item\"><span class=\"pagination__span\">...</span></li>";

					}

				}

			}

			// Disable next button if we're on the last page
			if ($current == $page_count) {

				$nextious = sprintf("<li class=\"pagination__item pagination__item--next\"><a href=\"#\" class=\"pagination__link pagination__link--disabled\" aria-label=\"Go to the next page\">%s</a></li>",
					'<span class="show-for-sr">Go to the next page</span>' .
					'<span class="pagination__icon pagination__icon--default">' . svg('chevron') . '</span>' .
					'<span class="pagination__icon pagination__icon--disabled">' . svg('chevron') . '</span>'
				);

			} else {

				$nextious = sprintf("<li class=\"pagination__item pagination__item--next\"><a href=\"%s\" data-page='%s' class=\"pagination__link\" aria-label=\"Go to the next page\">%s</a></li>",
					$next_page,
					$next_page_number,
					'<span class="show-for-sr">Go to the next page</span>' .
					'<span class="pagination__icon pagination__icon--default">' . svg('chevron') . '</span>' .
					'<span class="pagination__icon pagination__icon--disabled">' . svg('chevron') . '</span>'
				);

			}

			$container = sprintf("<ul class=\"pagination__list clearfix\" role=\"navigation\" aria-label=\"Pagination\">%s%s%s</ul>",
				$previous,
				implode("", $pages_to_print),
				$nextious
			);

			return ra($container);

		} else {
			return '';
		}
	}
