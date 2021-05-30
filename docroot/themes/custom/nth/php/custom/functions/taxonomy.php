<?php

	// Taxonomy related helper functions

	use Drupal\taxonomy\Entity\Vocabulary;
	use Drupal\taxonomy\Entity\Term;
	use Drupal\Core\Entity\EntityTypeManagerInterface;

	/**
	 * Get an array of term ids from a taxonomy field.
	 *
	 * @param $field
	 *
	 * @return array
	 */
	function getTagsArray($field)
	{

		$t = array();

		foreach ($field as $key => $f) {
			$t[] = $f->target_id;
		}

		return $t;

	}

	/**
	 * Get name and ID from a term ID as an array.
	 *
	 * @param $tid
	 *
	 * @return array
	 */
	function getTermInfo($tid)
	{

		$return = array();

		if (!empty($tid)) {

			$id = $tid;
			$name = Term::load($tid)->get('name')->value;

			$return = array(
				'id'   => $id,
				'name' => $name
			);

		}

		return $return;

	}

	/**
	 * Get a more elaborate array of information from a taxonomy field.
	 *
	 * Returns three arrays, an array of label names, an array of ids, and
	 * an associated array of labels and ids, for various purposes and uses.
	 *
	 * You can also pass it an array of ids and it will mark those terms as
	 * active, if you need to use the associated array to generate checkboxes or
	 * form elements of some kind.
	 *
	 * @param       $node
	 * @param       $field
	 * @param array $checked
	 *
	 * @return array
	 */
	function getTermsFromField($node, $field, $checked = array())
	{

		$return = array(
			'labels'     => array(),
			'tids'       => array(),
			'associated' => array()
		);

		if ($node->hasField($field)) {

			$terms = $node->get($field);

			if (!empty($terms)) {

				foreach ($terms as $term) {

					$t = Term::load($term->target_id);

					if (!empty($t)) {

						$active = false;
						if (!empty(in_array($t->get('tid')->value, $checked))) {
							$active = true;
						}

						$return['labels'][] = $t->get('name')->value;
						$return['tids'][] = $t->get('tid')->value;
						$return['associated'][] = array(
							'label'  => $t->get('name')->value,
							'safe'   => gen_slug($t->get('name')->value),
							'tid'    => $t->get('tid')->value,
							'active' => $active
						);

					}

				}

			}

		}

		return $return;

	}

	/**
	 * Returns detailed info arrays from a vocabulary.
	 *
	 * You will need to pass the machine name of the vocabulary as the first parameter.
	 *
	 * @param       $vocabulary
	 * @param array $checked
	 *
	 * @return array
	 */
	function getTagsArrayFromVocabulary($vocabulary, $checked = array())
	{

		$return = array();

		$vids = Vocabulary::loadMultiple();
		foreach ($vids as $vid) {
			if ($vid->id() == $vocabulary) {
				$container = \Drupal::getContainer();
				$terms = \Drupal::EntityTypeManager()->getStorage('taxonomy_term')->loadTree($vid->id());

				if (!empty($terms)) {
					foreach ($terms as $term) {

						$active = false;
						if (!empty(in_array($term->tid, $checked))) {
							$active = true;
						}

						$return['labels'][] = $term->name;
						$return['tids'][] = $term->tid;
						$return['associated'][] = array(
							'label'  => $term->name,
							'safe'   => gen_slug($term->name),
							'tid'    => $term->tid,
							'active' => $active
						);
					}
				}
				break;
			}
		}

		return $return;

	}

	/**
	 * Returns an array of specific taxonomy terms from a Taxonomy field, or if that field is
	 * empty, returns all of the terms from the related vocabulary.
	 *
	 * Used primarily to get curated filters or tags for finder pages.
	 *
	 * @param       $node       - Node with field. Usually the current node.
	 * @param       $field      - Field machine name to pull terms from.
	 * @param       $vocabulary - Fallback vocabulary to pull all terms from.
	 * @param array $checked    - Active terms that should be checked or chosen by default
	 *
	 * @return array|mixed - Returns associated array or empty array.
	 */
	function getTagsFromFieldOrVocabulary($node, $field, $vocabulary, $checked = array())
	{

		$return = array();

		$_return = getTermsFromField($node, $field, $checked);
		if (!empty($_return)) {
			$return = $_return;
		} else {
			$_return = getTagsArrayFromVocabulary($vocabulary, $checked);
			if (!empty($_return)) {
				$return = $_return;
			}
		}

		return $return;

	}

	/**
	 * Helper to get a clean taxonomy array for a Finder from a taxonomy field,
	 * rather than doing the work yourself to arrange the values.
	 *
	 * @param $field
	 * @param $tids
	 *
	 * @return array
	 */
	function getTaxonomyFinderArray($field, $tids)
	{

		$_tids = $tids;
		if (array_key_exists('tids', $tids)) {
			$_tids = $tids['tids'];
		}

		return array(
			'field' => $field,
			'value' => $_tids
		);

	}

	function getActiveTaxonomy($associated)
	{

		$return = array();

		if (!empty($associated)) {

			if (!empty($associated['associated'])) {
				$associated = $associated['associated'];
			}

			foreach ($associated as $term) {

				if ($term['active'] === true) {
					$return[] = $term;
				}

			}

		}

		return $return;

	}
