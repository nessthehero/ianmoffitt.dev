<?php

	// Load one or more nodes as rendered markup

	use \Drupal\node\Entity\Node;

	/**
	 * Load single node and return rendered markup
	 *
	 * @param        $nid
	 * @param string $view_mode
	 *
	 * @return string
	 */
	function load_node($nid, $view_mode = 'full')
	{

		$node = Node::load($nid);

		$view_builder = \Drupal::entityTypeManager()->getViewBuilder('node');

		$builder = '';

		if (!empty($node) && $node->isPublished()) {

			$build = $view_builder->view($node, $view_mode);

			$builder = render($build);

		}

		return $builder;

	}

	/**
	 * Load multiple nodes and return an array of markup.
	 *
	 * It is meant to be provided an array from a Finder, but as long as it is
	 * an array of items with 'nid' as a key that stores a valid node ID,
	 * it will work with this helper.
	 *
	 * @param        $finder_nodes
	 * @param string $view_mode
	 *
	 * @return array
	 */
	function load_nodes($finder_nodes, $view_mode = 'full')
	{

		$builder = array();

		if (!empty($finder_nodes)) {

			foreach ($finder_nodes as $key => $fn) {
				$builder[] = load_node($fn['nid'], $view_mode);
			}

		}

		return array_filter($builder);

	}

	/**
	 * Return array of node ids similar to finder, pulled from a node reference field
	 * @param $node
	 * @param $field
	 *
	 * @return array
	 */
	function get_node_references($node, $field) {

		$refs = array();

		if ($node->hasField($field)) {

			$_refs = $node->get($field);

			if (!empty($_refs)) {
				foreach ($_refs as $r) {
					$refs[] = array(
						'nid' => $r->target_id
					);
				}
			}

		}

		return $refs;

	}

	function get_reference_nids($node, $field) {

		$refs = array();

		if ($node->hasField($field)) {

			$_refs = $node->get($field);

			if (!empty($_refs)) {
				foreach ($_refs as $r) {
					$refs[] = $r->target_id;
				}
			}

		}

		return $refs;


	}

	function get_finder_nids($finder_nodes)
	{

		$refs = array();

		if (!empty($finder_nodes)) {

			foreach ($finder_nodes as $key => $fn) {
				$refs[] = $fn['nid'];
			}

		}

		return $refs;

	}

	function load_node_references($node, $field, $mode = 'full') {

		$_nodes = get_node_references($node, $field);

		return load_nodes($_nodes, $mode);

	}

	function load_search_result($item, $view_mode = 'full')
	{

		$builder = '';

		$original = $item->getOriginalObject(false);

		if (!empty($original)) {

			$node = $item->getOriginalObject()->getEntity();

			$view_builder = \Drupal::entityTypeManager()->getViewBuilder('node');

			if (!empty($node) && $node->isPublished()) {

				$build = $view_builder->view($node, $view_mode);

				$builder = render($build);

			}

		}

		return $builder;

	}

	function load_search_results($search_items, $view_mode = 'full') {

		$builder = array();

		if (!empty($search_items)) {

			foreach ($search_items as $key => $item) {
				$builder[] = load_search_result($item, $view_mode);
			}

		}

		return array_filter($builder);

	}

	function load_site_search_result($search_item)
	{

		$builder = array();

		$original = $search_item->getOriginalObject(false);

		if (!empty($original)) {

			$node = $search_item->getOriginalObject()->getEntity();

			if (!empty($node) && $node->isPublished()) {

				if ($node->hasField('field_heading')) {
					$heading = $node->get('field_heading')->value;
				} else {
					$heading = $node->get('title')->value;
				}

				$nid = $node->get('nid')->value;

				$alias = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $nid);

				$builder = array(
					'url' => $alias,
					'title' => $heading,
					'excerpt' => $search_item->getExcerpt()
				);

			}

		}

		return $builder;

	}

	function load_site_search_result_node($item) {

		$node = null;

		$original = $item->getOriginalObject(false);

		if (!empty($original)) {

			$node = $item->getOriginalObject()->getEntity();

		}

		return $node;

	}

	function load_site_search_result_nodes($search_items) {

		$nodes = array();

		if (!empty($search_items)) {

			foreach ($search_items as $key => $item) {
				$nodes[] = load_site_search_result_node($item);
			}

		}

		return array_filter($nodes);

	}

	function load_site_search($search_items)
	{

		$builder = array();

		if (!empty($search_items)) {

			foreach ($search_items as $key => $item) {
				$builder[] = load_site_search_result($item);
			}

		}

		return array_filter($builder);

	}

	function get_current_node() {

		$node = \Drupal::routeMatch()->getParameter('node');
		if ($node instanceof \Drupal\node\NodeInterface) {
			// You can get nid and anything else you need from the node object.
			return $node;
		} else {
			return null;
		}

	}

	function get_current_node_id() {

		$node = get_current_node();
		if (!empty($node)) {
			return $node->id();
		} else {
			return $node;
		}

	}

	function is_current_node($nid) {

		$current_nid = get_current_node_id();

		return $nid === $current_nid;

	}
