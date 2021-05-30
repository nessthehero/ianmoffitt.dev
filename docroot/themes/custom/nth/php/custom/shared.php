<?php

	// Manage variable setting for complex page features here, instead of cluttering up
	// node.php or page.php. This is also useful for repeating variable setting that is common
	// across nodes and pages.

	function build_masthead($node, &$variables, $view_mode = 'full')
	{

		$variables['masthead'] = '';

		if ($node->hasField('field_masthead') && !$node->field_masthead->isEmpty()) {

			$masthead = $node->get('field_masthead');

			$variables['masthead'] = load_paragraphs($masthead, $view_mode);

		}

	}
