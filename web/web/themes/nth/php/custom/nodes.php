<?php

	use \Drupal\node\Entity\Node;

	function load_node($nid, $view_mode = 'full') {

		$node = Node::load($nid);

		$view_builder = \Drupal::entityTypeManager()->getViewBuilder('node');

		$builder = '';

		$build = $view_builder->view($node, $view_mode);

		$builder = render($build);

		return $builder;

	}

	function load_nodes($finder_nodes, $view_mode = 'full') {

		$builder = array();

		foreach ($finder_nodes as $key => $fn) {
			$builder[] = load_node($fn['nid'], $view_mode);
		}

		return $builder;

	}
