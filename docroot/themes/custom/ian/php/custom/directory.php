<?php
//
//// Not used . . . yet
//
//class PeopleQuery {
//
//	protected $posts = array();
//	protected $rawPosts = array();
//	protected $cid = array(); // Department
//	protected $tid = array(); // Classification
//	protected $sort = 'relevance';
//	protected $mode = 'nonstrict';
//	protected $letters = array();
//	protected $machine = array('faculty');
//
//	const MAX_POSTS = 10;
//	const PAGE_SIZE = 10;
//
//	public function __construct($taxonomy, $sort = 'relevance', $mode = 'nonstrict') {
//
//		$this->taxonomy = array_filter($taxonomy);
//		$this->sort = $sort;
//		$this->mode = $mode;
//
//		$this->filter();
//
//	}
//
//	protected function filter() {
//
//		$cached = '';
//
//		$cache_tax = '';
//		if (!empty($this->taxonomy) && is_array($this->taxonomy)) {
//			foreach ($this->taxonomy as $filter) {
//				$cache_tax .= '-' . implode('-', $filter['value']);
//			}
//		}
//
//		$cache_key = "news-" . date("Y-m-d-h", time()) . $cache_tax;
//
//		$cache_time = '+1 hours';
//		$expire = strtotime($cache_time, time());
//
//		if ($cached = cache_get($cache_key, 'cache')) {
//			if (isset($cached->data)) {
//				$this->posts = $cached->data;
//			}
//		}
//
//		if (count($this->posts) == 0) {
//
//			$query = new EntityFieldQuery();
//			$this->posts = array();
//			$this->rawPosts = array();
//
//			$query->entityCondition('entity_type', 'node')
//	        	  ->entityCondition('bundle', $this->machine)
//		          ->propertyCondition('status', 1);
//
//			if (!empty($this->taxonomy) && is_array($this->taxonomy)) {
//				foreach ($this->taxonomy as $filter) {
//					if (count($filter['value']) > 0) {
//						switch ($this->mode) {
//							case 'strict':
//								$query->fieldCondition($filter['field'], 'tid', $filter['value']);
//								break;
//
//							default:
//								$query->fieldCondition($filter['field'], 'tid', $filter['value'], 'IN');
//								break;
//						}
//					}
//				}
//			}
//
//			$query->fieldOrderBy('field_last_name', 'value', 'ASC');
//
//			$query->propertyOrderBy('sticky', 'DESC');
//
//		    if ($this->sort == 'date') {
//		        $query->propertyOrderBy('created', 'DESC');
//		    }
//
//		    $query->execute();
//
//		    if (isset($query->ordered_results) && count($query->ordered_results) > 0) {
//		        foreach ($query->ordered_results as $row) {
//		            $this->posts[] = node_load($row->entity_id);
//		        }
//		    }
//
//			cache_set($cache_key, $this->posts, 'cache', $expire);
//
//		}
//
//		$this->rawPosts = $this->posts;
//
//		$this->buildLetters();
//
//	}
//
//	public function resetFiltering() {
//
//		$this->posts = $this->rawPosts;
//
//	}
//
//	public function results($amt = MAX_POSTS, $offset = 0) {
//
//		$slice = array_slice($this->posts, ($offset * $amt), $amt);
//
//		return $slice;
//
//	}
//
//	public function filterByTaxonomy($field, $tid_array) {
//
//		if (!empty($tid_array) && is_array($tid_array)) {
//			foreach ($this->posts as $j => $p) {
//	            $tags = nv($p, $field);
//	            $check_tax = array();
//
//	            if (!empty($tags['tid'])) {
//	                $check_tax[] = $tags['tid'];
//	            } else {
//	                foreach ($tags as $key => $value) {
//	                    if (!empty($value['tid'])) {
//	                        $check_tax[] = $value['tid'];
//	                    }
//	                }
//	            }
//
//	            foreach ($tid_array as $key => $value) {
//	                if (!in_array($value, $check_tax)) {
//	                    unset($this->posts[$j]);
//	                }
//	            }
//			}
//		}
//
//	}
//
//	public function filterByLetter($letter) {
//
//		if (!empty($letter)) {
//
//			if ($this->letters[$letter] == 1) {
//
//				foreach ($this->posts as $j => $p) {
//
//					$lastname = nv($p, 'field_last_name');
//
//					if (empty($lastname)) {
//						$lastname = $p->title;
//					}
//
//					$first = strtolower($lastname[0]);
//
//					if ($letter != $first) {
//						unset($this->posts[$j]);
//					}
//
//				}
//
//			}
//
//		}
//
//	}
//
//	public function count($filtered = false) {
//
//		if ($filtered) {
//			return count($this->posts);
//		} else {
//			return count($this->rawPosts);
//		}
//
//	}
//
//	public function pageCount($amt = 10) {
//
//		return ceil(count($this->posts) / $amt);
//
//	}
//
//	private function buildLetters() {
//
//		$l = array();
//
//		foreach (range('a', 'z') as $let) {
//			$l[$let] = 0;
//		}
//
//		foreach ($this->posts as $post) {
//
//			$lastname = nv($post, 'field_last_name');
//
//			if (empty($lastname)) {
//				$lastname = $post->title;
//			}
//
//			$first = strtolower($lastname[0]);
//
//			$l[$first] = 1;
//
//		}
//
//		$this->letters = $l;
//
//	}
//
//	public function getLetters() {
//		return $this->letters;
//	}
//
//}
//
//function build_people_search($node, &$variables, &$_q) {
//
//	$offset = 0;
//	if (!empty($_q['page']) && is_numeric($_q['page'])) {
//		$offset = $_q['page'];
//	}
//
//	$variables['offset'] = $offset;
//	$variables['prev_page'] = $offset;
//	$variables['current_page'] = $offset + 1;
//	$variables['next_page'] = $offset + 2;
//
//	$t_type = getTagsArray($node, 'field_faculty_type_tags');
//	$t_dept = getTagsArray($node, 'field_department_tags');
//	$t_programs = getTagsArray($node, 'field_program_tags');
//	$t_labs = getTagsArray($node, 'field_lab_tags');
//	$t_centers = getTagsArray($node, 'field_center_tags');
//	$t_projects = getTagsArray($node, 'field_project_tags');
//
//	$taxonomy = array(
//		array(
//			'field' => 'field_faculty_type_tags',
//			'value' => $t_type
//		),
//		array(
//			'field' => 'field_department_tags',
//			'value' => $t_dept
//		),
//		array(
//			'field' => 'field_program_tags',
//			'value' => $t_programs
//		),
//		array(
//			'field' => 'field_lab_tags',
//			'value' => $t_labs
//		),
//		array(
//			'field' => 'field_center_tags',
//			'value' => $t_centers
//		),
//		array(
//			'field' => 'field_project_tags',
//			'value' => $t_projects
//		)
//	);
//
//	$variables['searching'] = false;
//	$variables['search_query'] = '';
//	$q_builder = array();
//
//	$q_type = '';
//	if (!empty($_q['type']) && is_numeric($_q['type'])) {
//		$q_type = explode(',', $_q['type']);
//		$variables['searching'] = true;
//	}
//
//	$q_dept = '';
//	if (!empty($_q['dept']) && is_numeric($_q['dept'])) {
//		$q_dept = explode(',', $_q['dept']);
//		$variables['searching'] = true;
//	}
//
//	$q_program = '';
//	if (!empty($_q['program']) && is_numeric($_q['program'])) {
//		$q_program = explode(',', $_q['program']);
//		$variables['searching'] = true;
//	}
//
//	$q_lab = '';
//	if (!empty($_q['lab']) && is_numeric($_q['lab'])) {
//		$q_lab = explode(',', $_q['lab']);
//		$variables['searching'] = true;
//	}
//
//	$q_center = '';
//	if (!empty($_q['center']) && is_numeric($_q['center'])) {
//		$q_center = explode(',', $_q['center']);
//		$variables['searching'] = true;
//	}
//
//	$q_project = '';
//	if (!empty($_q['project']) && is_numeric($_q['project'])) {
//		$q_project = explode(',', $_q['project']);
//		$variables['searching'] = true;
//	}
//
//	$variables['active_letter'] = $q_letter;
//
//	$people = new PeopleQuery($nd, $nc);
//	$people->filterByTaxonomy('field_faculty_type_tags', $q_type);
//	$people->filterByTaxonomy('field_department_tags', $q_dept);
//	$people->filterByTaxonomy('field_program_tags', $q_program);
//	$people->filterByTaxonomy('field_lab_tags', $q_lab);
//	$people->filterByTaxonomy('field_center_tags', $q_center);
//	$people->filterByTaxonomy('field_project_tags', $q_project);
//
//	$q_debug = nvl($_q, 'debug');
//
//	$variables['letters'] = $people->getLetters();
//
//	$variables['people_query'] = $people;
//	$variables['people'] = $people->results(10, $variables['offset']);
//	if (!empty($q_debug)) {
//		$variables['people_count'] = (int) $q_debug; //$people->count(true);
//		$variables['people_count_un'] = (int) $q_debug; //$people->count();
//		$variables['page_count'] = (int) $q_debug / 10; //$people->pageCount();
//	} else {
//		$variables['people_count'] = $people->count(true);
//		$variables['people_count_un'] = $people->count();
//		$variables['page_count'] = $people->pageCount();
//	}
//
//}
