<?php

	// Implemented for Curry
	function primary_nav($menu = 'main-menu') {

		$nav = menu_tree_all_data($menu);

		if (count($nav) > 0):

			$output = "";

			$count = 0;
			foreach ($nav as $i => $item) {

				if ($item["link"]["hidden"] == 0) {

					$pAttr = array_merge(array(), $item["link"]["options"]["attributes"], $item["link"]["localized_options"]["attributes"]);

					$pAttr['class'][] = 'primary-nav-link';

					$output .= "<li class='primary-nav-list-item " . (($item["link"]["has_children"] == "1") ? " has-children" : "") . "'>";
					$output .= l($item["link"]["link_title"], $item["link"]["link_path"], array('attributes' => $pAttr));

					if ($item["link"]["has_children"] == "1") {

						$output .= '<span class="primary-child-nav-trigger" aria-label="Toggle child navigation of \'' . $item["link"]["link_title"] . '\' list item">';
						$output .= svg('plus2');
						$output .= svg('minus2');
						$output .= '</span>';

						$output .= "<ul class='primary-child-nav clearfix'>";

						$below = $item["below"];

						foreach ($below as $j => $bitem) {

							if ($bitem["link"]["hidden"] == 0) {

								$bpAttr = array_merge(array(), $bitem["link"]["options"]["attributes"], $bitem["link"]["localized_options"]["attributes"]);

								$bpAttr['class'][] = 'primary-child-nav-link';

								$output .= '<li class="primary-child-nav-list-item">' . l($bitem["link"]["link_title"], $bitem["link"]["link_path"], array('attributes' => $bpAttr)) . "</li>";

							}

						};

						$output .= "</ul>";

					}

					$output .= "</li>";

					$count += 1;

				}

			}

			print $output;

		endif;

	}

	// Implemented for Curry
	function secondary_nav() {

		$nav = menu_tree_all_data('menu-audience-menu');

		if (count($nav) > 0):
?>

<nav id="js-secondary-nav" class="secondary-nav" role="navigation">
	<ul class="clearfix">
		<?php
			$output = "";

			foreach ($nav as $i => $item) {

				if ($item["link"]["hidden"] == 0) {

					$pAttr = array_merge(array(), $item["link"]["options"]["attributes"], $item["link"]["localized_options"]["attributes"]);

					unset($pAttr['class']);

					$pAttr['class'][] = 'secondary-nav-link';

					$output .= "<li class='secondary-nav-list-item'>" . l($item["link"]["link_title"], $item["link"]["link_path"], array('attributes' => $pAttr)) . "</li>";

				}

			}

			print $output;
		?>
	</ul>
</nav>

<?php
		endif;

	}

	function footer_nav() {

		$nav = menu_tree_all_data('menu-footer-menu');

		if (count($nav) > 0):
?>

<nav class="footer-links" role="navigation">
	<ul class="clearfix">
		<?php
			$output = "";

			foreach ($nav as $i => $item) {

				if ($item["link"]["hidden"] == 0) {

					$pAttr = array_merge(array(), $item["link"]["options"]["attributes"], $item["link"]["localized_options"]["attributes"]);

					$pAttr['class'][] = 'footer-link';

					$output .= "<li>" . l($item["link"]["link_title"], $item["link"]["link_path"], array('attributes' => $pAttr)) . "</li>";

				}

			}

			print $output;
		?>
	</ul>
</nav>

<?php
		endif;

	}

/**
 * Renders Major CTAs. Pass two strings and if they match, the menu will be rendered.
 * @param  string $check  First string to check
 * @param  string $render Second string to check
 * @return void           renders a menu
 */
	function major_ctas($check = '', $render = '') {

		if ($check == $render):
			$nav = menu_tree_all_data('menu-major-ctas');
			$render_array = array();
			$links = array();

			$icons = array(
				'pencil',
				'pin',
				'info'
			);

			if (count($nav) >= 3):

				$render_array[] = array_shift($nav);
				$render_array[] = array_shift($nav);
				$render_array[] = array_shift($nav);

				foreach ($render_array as $key => $render) {
					$render["link"]["options"]["html"] = true;

					$options = array_merge(array(), $render["link"]["options"], $render["link"]["localized_options"]);

					$icon = $icons[$key];

					if (!empty($options['attributes']['data-svg-icon'])) {
						$icon = $options['attributes']['data-svg-icon'];
					}

					if ($render['link']['hidden'] == 0) {

						$links[] = l(
							svgicon($icon) . '<span class="text">' . $render["link"]["link_title"] . '</span>',
							$render["link"]["link_path"],
							$options
						);

					}
				}

?>

<section class="major-ctas">

	<ul>
		<?php foreach ($links as $key => $link): ?>
		<li>
			<?php print $link; ?>
		</li>
		<?php endforeach; ?>
	</ul>

</section>

<?php
			endif;
		endif;

	}
