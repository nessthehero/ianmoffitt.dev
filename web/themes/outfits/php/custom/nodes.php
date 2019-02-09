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

		if (!empty($node)) {

			$build = $view_builder->view($node, $view_mode);

			$builder = render($build);

		}

		return $builder;

	}

	/**
	 * Load multiple nodes and return an array of markup
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

		return $builder;

	}
