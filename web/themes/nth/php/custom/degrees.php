<?php

class DegreesQuery {

	protected $posts = array();
	protected $rawPosts = array();
	protected $taxonomy = array();
	protected $sort = 'date';
	protected $mode = 'nonstrict';
	protected $machine = array('degree');

	const MAX_POSTS = 4;
	const PAGE_SIZE = 10;

	public function __construct($taxonomy, $sort = 'date', $mode = 'nonstrict') {

		$this->taxonomy = array_filter($taxonomy);
		$this->sort = $sort;
		$this->mode = $mode;

		$this->filter();

	}

	protected function filter() {

		$cached = '';

		$cache_tax = '';
		if (!empty($this->taxonomy) && is_array($this->taxonomy)) {
			foreach ($this->taxonomy as $filter) {
				$cache_tax .= '-' . implode('-', $filter['value']);
			}
		}

		$cache_key = "degree-" . date("Y-m-d-h", time()) . $cache_tax;

		$cache_time = '+1 hours';
		$expire = strtotime($cache_time, time());

		if ($cached = cache_get($cache_key, 'cache')) {
			if (isset($cached->data)) {
				$this->posts = $cached->data;

				// drupal_set_message(t('Degrees Cached'), 'status', FALSE);
			}
		}

		if (count($this->posts) == 0) {

			$query = new EntityFieldQuery();
			$this->posts = array();
			$this->rawPosts = array();

			$query->entityCondition('entity_type', 'node')
	        	  ->entityCondition('bundle', $this->machine)
		          ->propertyCondition('status', 1);

			if (!empty($this->taxonomy) && is_array($this->taxonomy)) {
				foreach ($this->taxonomy as $filter) {
					if (count($filter['value']) > 0) {
						switch ($this->mode) {
							case 'strict':
								$query->fieldCondition($filter['field'], 'tid', $filter['value']);
								break;

							default:
								$query->fieldCondition($filter['field'], 'tid', $filter['value'], 'IN');
								break;
						}
					}
				}
			}

			// Sort by title
			$query->propertyOrderBy('title', 'ASC');

			// Move sticky to top
			$query->propertyOrderBy('sticky', 'DESC');

		    if ($this->sort == 'date') {
		        $query->propertyOrderBy('created', 'DESC');
		    }

		    $query->execute();

		    if (isset($query->ordered_results) && count($query->ordered_results) > 0) {
		        foreach ($query->ordered_results as $row) {
		            $this->posts[] = node_load($row->entity_id);
		        }
		    }

			cache_set($cache_key, $this->posts, 'cache', $expire);

		}

		$this->rawPosts = $this->posts;

	}

	public function resetFiltering() {

		$this->posts = $this->rawPosts;

	}

	public function results($amt = MAX_POSTS, $offset = 0) {

		if ($amt == -1) {
			return $this->posts;
		} else {
			$slice = array_slice($this->posts, ($offset * $amt), $amt);
			return $slice;
		}

	}

	public function filterByKeyword($keyword = '') {

		$filteredposts = array();

		if (!empty($keyword)) {

		   	foreach ($this->posts as $j => $p) {

		   		$title = $p->title;
		   		$body = nv($p, 'body');
	            $summ = nv($p, 'body', 'teaser');

		   		$haystack = strtolower($title.' '.render($body).' '.render($summ));
		   		$needle = strtolower($key);

	    		if (strpos($haystack, $needle) !== false) {
	    			$filteredposts[] = $p;
	    		}

	    	}

			$this->posts = $filteredposts;

	    }

	}

	public function filterByTaxonomy($field, $tid_array) {

		if (!empty($tid_array) && is_array($tid_array)) {
			foreach ($this->posts as $j => $p) {
	            $tags = nv($p, $field);
	            $check_tax = array();

	            if (!empty($tags['tid'])) {
	                $check_tax[] = $tags['tid'];
	            } else {
	                foreach ($tags as $key => $value) {
	                    if (!empty($value['tid'])) {
	                        $check_tax[] = $value['tid'];
	                    }
	                }
	            }

	            foreach ($tid_array as $key => $value) {
	                if (!in_array($value, $check_tax)) {
	                    unset($this->posts[$j]);
	                }
	            }
			}
		}

	}

	public function sortByDate() {

		$this->sort = 'date';

		$this->filter();

	}

	public function count($filtered = false) {

		if ($filtered) {
			return count($this->posts);
		} else {
			return count($this->rawPosts);
		}

	}

	public function pageCount($amt = 10) {

		return ceil(count($this->posts) / $amt);

	}

}

function build_degree_search($node, &$variables, &$_q) {

	$offset = 0;
	if (!empty($_q['page']) && is_numeric($_q['page'])) {
		$offset = $_q['page'];
	}

	$variables['offset'] = $offset;
	$variables['prev_page'] = $offset;
	$variables['current_page'] = $offset + 1;
	$variables['next_page'] = $offset + 2;

	$t_labs = getTagsArray($node, 'field_lab_tags');
	$t_centers = getTagsArray($node, 'field_center_tags');

	$taxonomy = array(
		array(
			'field' => 'field_lab_tags',
			'value' => $t_labs
		),
		array(
			'field' => 'field_center_tags',
			'value' => $t_centers
		)
	);

	$variables['searching'] = false;
	$variables['search_query'] = '';
	$q_builder = array();

	$q_center = '';
	if (!empty($_q['center']) && is_numeric($_q['center'])) {
		$q_center = explode(',', $_q['center']);
		$variables['searching'] = true;
	}

	$q_lab = '';
	if (!empty($_q['lab']) && is_numeric($_q['lab'])) {
		$q_lab = explode(',', $_q['lab']);
		$variables['searching'] = true;
	}

	$degree = new DegreesQuery($taxonomy);
	$degree->filterByTaxonomy('field_lab_tags', $q_center);
	$degree->filterByTaxonomy('field_center_tags', $q_lab);

	$degree->filterByDepartment($q_center);
	$degree->filterByResearch($q_lab);

	$q_debug = nvl($_q, 'debug');

	$variables['degree_query'] = $degree;

	$count = 10;
	if (!empty($variables['searching'])) {
		$count = $degree->count();
		$variables['offset'] = 0;
	}

	$variables['degree'] = $degree->results($count, $variables['offset']);
	if (!empty($q_debug)) {
		$variables['degree_count'] = (int) $q_debug; //$degree->count(true);
		$variables['degree_count_un'] = (int) $q_debug; //$degree->count();
		$variables['page_count'] = (int) $q_debug / 10; //$degree->pageCount();
	} else {
		$variables['degree_count'] = $degree->count(true);
		$variables['degree_count_un'] = $degree->count();
		$variables['page_count'] = $degree->pageCount();
	}

}
