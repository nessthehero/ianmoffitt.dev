<?php

	use \Drupal\node\Entity\Node;
	use \Drupal\paragraphs\Entity\Paragraph;

	function load_node($nid, $view_mode = 'full')
	{

		$node = Node::load($nid);

		$view_builder = \Drupal::entityTypeManager()->getViewBuilder('node');

		$builder = '';

		$build = $view_builder->view($node, $view_mode);

		$builder = render($build);

		return $builder;

	}

	function load_nodes($finder_nodes, $view_mode = 'full')
	{

		$builder = array();

		foreach ($finder_nodes as $key => $fn) {
			$builder[] = load_node($fn['nid'], $view_mode);
		}

		return $builder;

	}

	function load_paragraphs($instance, $view_mode = 'full', $foundation_detect_end = false)
	{

		$view_builder = \Drupal::entityTypeManager()->getViewBuilder('paragraph');

		$builder = array();

		foreach ($instance as $key => $_p) {

			$paragraph = Paragraph::load($_p->get('target_id')->getValue());

			$build = $view_builder->view($paragraph, $view_mode);

			if (!empty($foundation_detect_end)) {
				if ($key == (count($instance) - 1)) {
					$build['#attributes']['class'][] = 'end';
				}
			}

			$builder[] = render($build);

		}

		return ra(implode($builder, ''));

	}
