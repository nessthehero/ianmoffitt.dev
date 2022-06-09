<?php

	namespace Nth\Helpers;

	// Load one or more nodes as rendered markup

	use \Drupal\node\Entity\Node;
	use \Drupal\core\Url;

  // TODO: Rewrite?

	class Nodes
	{

		/**
		 * Load single node and return rendered markup
		 *
		 * @param        $nid
		 * @param string $view_mode
		 *
		 * @return string
		 */
		public static function load_node($nid, $view_mode = 'full')
		{

			$node = Node::load($nid);

			$builder = '';

			if (!empty($node) && $node->isPublished()) {
				$builder = self::render_node($node, $view_mode);
			}

			return $builder;

		}

		public static function render_node($node, $view_mode = 'full')
		{

			$view_builder = \Drupal::entityTypeManager()->getViewBuilder('node');

			$builder = '';

			if (!empty($node) && $node->isPublished()) {

				$build = $view_builder->view($node, $view_mode);

				$builder = \Drupal::service('renderer')->render($build);

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
		public static function load_nodes($finder_nodes, $view_mode = 'full')
		{

			$builder = array();

			if (!empty($finder_nodes)) {

				foreach ($finder_nodes as $key => $fn) {
					$builder[] = self::load_node($fn['nid'], $view_mode);
				}

			}

			return array_filter($builder);

		}

		/**
		 * Return array of node ids similar to finder, pulled from a node reference field
		 *
		 * @param $node
		 * @param $field
		 *
		 * @return array
		 */
		public static function get_node_references($node, $field)
		{

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

		public static function get_node_link($nid) {

			return \Drupal::service('path_alias.manager')->getAliasByPath('/node/' . $nid);


		}

		public static function get_node_links($node, $field)
		{

			$links = array();
			$refs = self::get_node_references($node, $field);

			if (!empty($refs)) {

				foreach (refs as $rid) {
					$alias = self::get_node_link($rid);

					if (!empty($alias)) {
						$links[] = $alias;
					}
				}

			}

		}

		public static function get_reference_nids($node, $field)
		{

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

		public static function get_finder_nids($finder_nodes)
		{

			$refs = array();

			if (!empty($finder_nodes)) {

				foreach ($finder_nodes as $key => $fn) {
					$refs[] = $fn['nid'];
				}

			}

			return $refs;

		}

		public static function load_node_references($node, $field, $mode = 'full')
		{

			$_nodes = self::get_node_references($node, $field);

			return self::load_nodes($_nodes, $mode);

		}

		public static function get_current_node()
		{

			$node = \Drupal::routeMatch()->getParameter('node');
			if ($node instanceof \Drupal\node\NodeInterface) {
				// You can get nid and anything else you need from the node object.
				return $node;
			} else {
				return null;
			}

		}

		public static function get_current_node_id()
		{

			$node = self::get_current_node();
			if (!empty($node)) {
				return $node->id();
			} else {
				return $node;
			}

		}

		public static function is_current_node($nid)
		{

			$current_nid = self::get_current_node_id();

			return $nid === $current_nid;

		}

		public static function get_node_from_path($path)
		{

			$node = false;
			$alias = \Drupal::service('path_alias.manager')->getPathByAlias($path);

			if (!empty($alias)) {
				$params = Url::fromUri("internal:" . $alias)->getRouteParameters();
				$entity_type = key($params);
				$node = \Drupal::entityTypeManager()->getStorage($entity_type)->load($params[$entity_type]);
			}

			return $node;

		}

		public static function get_node_title($node)
		{

			$heading = $node->get('title')->value;

			if ($node->hasField('field_heading')) {
				$heading = $node->get('field_heading')->value;
			}

			if ($node->hasField('field_display_heading') && !$node->field_display_heading->isEmpty()) {
				$heading = $node->get('field_display_heading')->value;
			}

			return parseHeading($heading);

		}

	}
