<?php

/**
 * Renders an svg icon with appropriate markup
 * @param  string  $icon        slug of icon
 * @param  boolean $screen      also render screenreader text (optional)
 * @param  string  $screen_text screenreader text to render (optional)
 * @return string               markup to render
 */
function svgicon($icon, $screen = false, $screen_text = '') {

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
 * Returns markup for a single svg icon
 * @param  string $icon icon name
 * @return string	    icon markup
 */
function svg($icon) {

	$r = "";

	if (is_string($icon)) {
		$r = "<svg class='icon icon-" . $icon . "'><use xlink:href='#icon-" . $icon . "'></use></svg>";
	}

	return $r;

}

/**
 * Returns markup for sharethis widget
 * @return void
 */
function share_this() {}

function contents($filename) {

	return file_get_contents(dirname(__DIR__) . '/partials/' . $filename . '.php');

}

/**
 * Includes partial from php/partials
 * @param  string $filename file name sans php
 * @return void
 */
function partial($filename) {

	global $base_url;
	global $theme;

	$theme_path = drupal_get_path('theme',$theme);

	include(dirname(__DIR__) . '/partials/' . $filename . '.php');

}

/**
 * Generate paging for indexes of content
 * @param  object  $node        Node object
 * @param  array  $q            Array of query string values
 * @param  integer  $current    Current Page (0 based)
 * @param  integer  $page_count Page Count
 * @param  boolean $ellipsis    Show ellipsis between page numbers (to reduce width) (only for more than 9 pages)
 * @return void                 Outputs markup to page
 */
function pagination($node, $q, $current, $page_count, $ellipsis = true) {

	// Build the url for the next page
	$q['page'] = $current;
	$next_page = '/' . nu($node->nid) . '?' . http_build_query($q);

	// Build the url for the previous page
	$q['page'] = $current - 2;
	$prev_page = '/' . nu($node->nid) . '?' . http_build_query($q);

	// Restore the current page to the query object
	$q['page'] = $current - 1;

	// Build out array of possible page numbers
	$n = range(1, $page_count);
	foreach($n as $k) {
		// safe => page number can render
		// number => real page number
		// ellipsis => render an ellipsis instead of a page number
		// current => this is the current page
		$pages_array[] = array(
			'safe' => false,
			'number' => $k,
			'ellipsis' => 'no',
			'current' => 'no'
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
		if (!empty($pages_array[$current - 2])) { $pages_array[$current - 2]['safe'] = true; } // prev page
		if (!empty($pages_array[$current])) { $pages_array[$current]['safe'] = true; } // next page

		// Determine the midpoint
		$midpoint = ceil($page_count / 2);

		// determine number of sections
		//
		// A "section" is a split between two safe pages. We need to count them to know where to put ellipses.
		$sections = 0;
		$flip = 0;

		foreach($pages_array as $key => $j) {
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
		foreach($pages_array as $key => $j) {

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
		foreach($pages_array as $key => $j) {
			$pages_array[$key]['safe'] = true;
			$pages_array[$key]['ellipsis'] = 'no';
		}

	}

	// Gotta have more than one page to show the pagination.
	if ($page_count > 1) {
?>
	<div class="pagination">

		<?php // Disable previous button if we're on page 1 ?>
		<?php if ($current == 1): ?>
			<span class="nav prev disabled">
				<span class="show-for-sr">Previous</span>
				<?php print svg('double-left-chevron'); ?>
			</span>
		<?php else: ?>
			<a href="<?php print $prev_page; ?>" class="nav prev">
				<span class="show-for-sr">Previous</span>
				<?php print svg('double-left-chevron'); ?>
			</a>
		<?php endif; ?>

		<?php // Start looping through page numbers ?>
		<ol>
			<?php foreach ($pages_array as $key => $page):

				// We're overriding the query page object so we can build URLs
				$q['page'] = $page['number'] - 1;

				?>

				<?php // If the page is safe to render OR it's an ellipsis, then we can render a list item... ?>
				<?php if (!empty($page['safe']) || $page['ellipsis'] == 'yes'): ?>
					<li><?php

					// If we're on a safe page...
					if (!empty($page['safe'])):

						// And it's the current page, we'll render a span...
						if ($page['current'] == 'yes'):

							printf("<span class='%s'>%s</span>",
								($page['number'] == 1) ? 'page first active' : 'page active',
								$page['number']
							);

						else:
						// Otherwise we'll render a link to that page

							printf("<a class='%s' href='%s'>%s</a>",
								($page['number'] == 1) ? 'page first' : 'page',
								'/' . nu($node->nid) . '?' . http_build_query($q),
								$page['number']
							);

						endif;

					endif;

					// If it's an ellipsis, we'll render that.
					if ($page['ellipsis'] == 'yes'):

						?><span class="ellipsis">...</span><?php

				 	endif;
					?></li>
				<?php endif; ?>

			<?php endforeach; ?>
		</ol>

		<?php // Disable next button if we're on the last page ?>
		<?php if ($current == $page_count): ?>
			<span class="nav next disabled">
				<span class="show-for-sr">Next</span>
				<?php print svg('double-right-chevron'); ?>
			</span>
		<?php else: ?>
			<a href="<?php print $next_page; ?>" class="nav next">
				<span class="show-for-sr">Next</span>
				<?php print svg('double-right-chevron'); ?>
			</a>
		<?php endif; ?>

	</div>
<?php
	}
}

