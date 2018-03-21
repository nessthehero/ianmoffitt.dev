<?php

use \Drupal\node\Entity\Node;

class EventsQuery {

	protected $posts = array();
	protected $rawPosts = array();
	protected $events = array();
	protected $rawEvents = array();
	protected $taxonomy = array();
	protected $sort = 'date';
	protected $mode = 'nonstrict';

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

		$cache_key = "events-" . date("Y-m-d-h", time()) . $cache_tax;

		$cache_time = '+1 hours';
		$expire = strtotime($cache_time, time());

		// if ($cached = \Drupal::cache()->get($cache_key)) {
		// 	if (isset($cached->data)) {
		// 		$this->posts = $cached->data;
		// 	}
		// }

		if (count($this->posts) == 0) {

			$query = \Drupal::entityQuery('node');

			$query->condition('type', 'event');

			$query->condition('status', 1);

			if (!empty($this->taxonomy) && is_array($this->taxonomy)) {
  				foreach ($this->taxonomy as $filter) {
  					if (count($filter['value']) > 0) {
  						switch ($this->mode) {
  							case 'strict':
  								$query->condition($filter['field'], $filter['value']);
  								break;

  							default:
  								$query->condition($filter['field'], $filter['value'], 'IN');
  								break;
  						}
  					}
  				}
  			}

			$posts = $query->execute();

			foreach ($posts as $key => $p) {
				$this->posts[] = $this->_load_data($p);
			}

			// $query = new EntityFieldQuery();
			// $this->posts = array();
			// $this->rawPosts = array();
			//
			// $query->entityCondition('entity_type', 'node')
	        // 	  ->entityCondition('bundle', array('event'))
		    //       ->propertyCondition('status', 1);
			//
			//  	if (!empty($this->taxonomy) && is_array($this->taxonomy)) {
  	// 			foreach ($this->taxonomy as $filter) {
  	// 				if (count($filter['value']) > 0) {
  	// 					switch ($this->mode) {
  	// 						case 'strict':
  	// 							$query->fieldCondition($filter['field'], 'tid', $filter['value']);
  	// 							break;
			//
  	// 						default:
  	// 							$query->fieldCondition($filter['field'], 'tid', $filter['value'], 'IN');
  	// 							break;
  	// 					}
  	// 				}
  	// 			}
  	// 		}
			//
			// $query->propertyOrderBy('sticky', 'DESC');
			//
		    // $query->execute();
			//
		    // if (isset($query->ordered_results) && count($query->ordered_results) > 0) {
		    //     foreach ($query->ordered_results as $row) {
		    //         $this->posts[] = node_load($row->entity_id);
		    //     }
		    // }
			//
			// cache_set($cache_key, $this->posts, 'cache', $expire);

			// \Drupal::cache()->set($cache_key, $this->posts);

		}

		$this->organizeEvents();

        $this->rawPosts = $this->posts;
        $this->rawEvents = $this->events;

	}

	private function _load_data($nid) {

		$node = Node::load($nid);
		$return = array();

		if ($node->hasField('field_date')) {

		    $title = $node->title->value;
		    if ($node->hasField('field_heading') && !$node->field_heading->isEmpty()) {
		        $title = $node->field_heading->value;
            }

            $return = array(
                'nid' => $nid,
                'sticky' => $node->isSticky(),
                'title' => $title,
                'start' => $node->field_date->start_date->getTimestamp(),
                'end' => $node->field_date->end_date->getTimestamp(),
                'start_formatted' => parse_date($node->field_date->start_date),
                'end_formatted' => parse_date($node->field_date->end_date)
            );

        }

        return $return;

	}

	public function resetFiltering() {

		$this->posts = $this->rawPosts;
        $this->rawEvents = $this->events;

	}

	public function results($amt = 10, $offset = 0) {

		if ($amt == -1) {
			return $this->events;
		} else {
			$slice = array_slice($this->events, ($offset * $amt), $amt);
			return $slice;
		}

	}

	public function remove($items) {

        foreach ($items as $key => $item) {

            foreach ($this->events as $j => $post) {

                if ($post['nid'] == $item['nid']) {
                    array_splice($this->posts, $j, 1);
                    array_splice($this->events, $j, 1);
                }

            }

        }

        return $this;

    }

	protected function organizeEvents() {

		$event_sort_by_date = array();
		$event_date_col = array();
		$sticky_events = array();

		foreach ($this->posts as $j => $p) {

		 	if (!empty($p['start'])) {

		 		$rawstart = $p['start'];
		 		$rawend = $p['end'];
		 		$now = \Drupal::time()->getCurrentTime();

		 		if ($rawstart >= $now || $rawend >= $now) {

		 			if (empty($p['sticky'])) {

		 			    $event_sort_by_date[] = array(
		 			        'date' => $rawstart,
                            'title' => $p['title'],
                            'nid' => $p['nid']
                        );

		 				$event_date_col[] = $rawstart;

		 			} else {

		 				$sticky_events[] = array(
		 					'date' => $rawstart,
                            'title' => $p['title'],
		 					'nid' => $p['nid']
		 				);

		 			}

		 		}

		 	}

		}

		array_multisort($event_date_col, SORT_ASC, $event_sort_by_date);

		$this->events = array_merge($sticky_events, $event_sort_by_date);

	}

	public function filterByKeyword($keyword = '') {

		// $filteredposts = array();
		//
		// if (!empty($keyword)) {
		//
		// 	//   	foreach ($this->posts as $j => $p) {
		// 	//
		// 	//   		$title = $p->title;
		// 	//   		$body = nv($p, 'body');
	    //     //     $summ = nv($p, 'body', 'teaser');
		// 	//
		// 	//   		$haystack = strtolower($title.' '.render($body).' '.render($summ));
		// 	//   		$needle = strtolower($key);
		// 	//
	    // 	// 	if (strpos($haystack, $needle) !== false) {
	    // 	// 		$filteredposts[] = $p;
	    // 	// 	}
		// 	//
	    // 	// }
		//
		// 	$this->posts = $filteredposts;
		//
	    // }
		//
		// $this->organizeEvents();

	}

	public function filterByMonth($month = 0) {

		// $filteredposts = array();
		//
		// if (!empty($month) && $month != 'all') {
		//
		// 	// foreach ($this->posts as $j => $p) {
		// 	//
		// 	// 	$pmonth = date('n', $p->created);
		// 	//
		// 	// 	if ($month == $pmonth) {
		// 	//
	    // 	// 		$filteredposts[] = $p;
		// 	//
		// 	// 	}
		// 	//
	    // 	// }
		//
		// 	$this->posts = $filteredposts;
		//
		// }
		//
		// $this->organizeEvents();

	}

	public function filterByYear($year = 0) {

		// $filteredposts = array();
		//
		// if (!empty($year) && $year != 'all') {
		//
		// 	// foreach ($this->posts as $j => $p) {
		// 	//
		// 	// 	$pyear = date('Y', $p->created);
		// 	//
		// 	// 	if ($year == $pyear) {
		// 	//
	    // 	// 		$filteredposts[] = $p;
		// 	//
		// 	// 	}
		// 	//
	    // 	// }
		//
		// 	$this->posts = $filteredposts;
		//
		// }
		//
		// $this->organizeEvents();

	}

	public function filterByTag($tid_array) {

		// if (!empty($tid_array) && is_array($tid_array)) {
		// 	foreach ($this->posts as $j => $p) {
	    //         $tags = nv($p, 'field_tags');
	    //         $check_tags = array();
		//
	    //         if (!empty($tags['tid'])) {
	    //             $check_tags[] = $tags['tid'];
	    //         } else {
	    //             foreach ($tags as $key => $value) {
	    //                 if (!empty($value['tid'])) {
	    //                     $check_tags[] = $value['tid'];
	    //                 }
	    //             }
	    //         }
		//
	    //         foreach ($tid_array as $key => $value) {
	    //             if (!in_array($value, $check_tags)) {
	    //                 unset($this->posts[$j]);
	    //             }
	    //         }
		// 	}
		//
		// 	$this->organizeEvents();
		// }

	}

	public function filterByDepartment($did_array) {

		// if (!empty($did_array) && is_array($did_array)) {
		// 	foreach ($this->posts as $j => $p) {
		// 		$depts = nv($p, 'field_departments');
		// 		$check_depts = array();
		//
		// 		if (!empty($depts['tid'])) {
		// 			$check_depts[] = $depts['tid'];
		// 		} else {
		// 			foreach ($depts as $key => $value) {
		// 				if (!empty($value['tid'])) {
		// 					$check_depts[] = $value['tid'];
		// 				}
		// 			}
		// 		}
		//
		// 		foreach ($did_array as $key => $value) {
		// 			if (!in_array($value, $check_depts)) {
		// 				unset($this->posts[$j]);
		// 			}
		// 		}
		// 	}
		//
		// 	$this->organizeEvents();
		// }

	}

	public function filterByEventTag($rid_array) {

		// if (!empty($rid_array) && is_array($rid_array)) {
		// 	foreach ($this->posts as $j => $p) {
		// 		$evnttag = nv($p, 'field_event_tags');
		// 		$check_evnttag = array();
		//
		// 		if (!empty($evnttag['tid'])) {
		// 			$check_evnttag[] = $evnttag['tid'];
		// 		} else {
		// 			foreach ($evnttag as $key => $value) {
		// 				if (!empty($value['tid'])) {
		// 					$check_evnttag[] = $value['tid'];
		// 				}
		// 			}
		// 		}
		//
		// 		foreach ($rid_array as $key => $value) {
		// 			if (!in_array($value, $check_evnttag)) {
		// 				unset($this->posts[$j]);
		// 			}
		// 		}
		// 	}
		//
		// 	$this->organizeEvents();
		// }

	}

	public function filterByDateRange($start, $end) {

		// if ($start != '' || $end != '') {
		// 	foreach ($this->posts as $j => $p) {
		// 		$event_date = get_event_current_date($p);
		//
		// 		if ($start != '') {
		// 			$startraw = DateTime::createFromFormat('m-d-Y', $start)->format('U');
		// 			$eventstartraw = strtotime($event_date['start']);
		//
		// 			if ($eventstartraw >= $startraw) {
		//
		// 				// You match! Do nothing!
		//
		// 			} else {
		//
		// 				unset($this->posts[$j]);
		//
		// 			}
		// 		}
		//
		// 		if ($end != '') {
		//
		// 			$endraw = DateTime::createFromFormat('m-d-Y', $end)->format('U');
		// 			$eventendraw = strtotime($event_date['end']);
		//
		// 			if ($eventendraw <= $endraw) {
		//
		// 				// You match! Do nothing!
		//
		// 			} else {
		//
		// 				unset($this->posts[$j]);
		//
		// 			}
		//
		// 		}
		//
		// 	}
		//
		// 	$this->organizeEvents();
		// }

	}

	public function sortByDate() {

		// $this->sort = 'date';
		//
		// $this->filter();

	}

	public function count($filtered = false) {

		// if ($filtered) {
		// 	return count($this->events);
		// } else {
		// 	return count($this->rawEvents);
		// }

	}

	public function pageCount($amt = 10) {

		// We're getting the page count of valid events, not posts
		// return ceil(count($this->events) / $amt);

	}

}

function build_events_search($node, &$variables, &$_q) {

	// $offset = 0;
	// if (!empty($_q['page']) && is_numeric($_q['page'])) {
	// 	$offset = $_q['page'];
	// }
	//
	// $variables['offset'] = $offset;
	// $variables['prev_page'] = $offset;
	// $variables['current_page'] = $offset + 1;
	// $variables['next_page'] = $offset + 2;
	//
	// $misc_tags = nv($node, 'field_tags');
	// $mt = array();
	// if (!empty($misc_tags)) {
	// 	if (!empty($misc_tags['tid'])) {
	// 		$mt[] = $misc_tags['tid'];
	// 	} else {
	// 		foreach ($misc_tags as $key => $value) {
	// 			if (!empty($value['tid'])) {
	// 				$mt[] = $value['tid'];
	// 			}
	// 		}
	// 	}
	// }
	//
	// $events_tags = nv($node, 'field_event_tags');
	// $et = array();
	// if (!empty($events_tags)) {
	// 	if (!empty($events_tags['tid'])) {
	// 		$et[] = $events_tags['tid'];
	// 	} else {
	// 		foreach ($events_tags as $key => $value) {
	// 			if (!empty($value['tid'])) {
	// 				$et[] = $value['tid'];
	// 			}
	// 		}
	// 	}
	// }
	//
	// $events_depts = nv($node, 'field_departments');
	// $ed = array();
	// if (!empty($events_depts)) {
	// 	if (!empty($events_depts['tid'])) {
	// 		$ed[] = $events_depts['tid'];
	// 	} else {
	// 		foreach ($events_depts as $key => $value) {
	// 			if (!empty($value['tid'])) {
	// 				$ed[] = $value['tid'];
	// 			}
	// 		}
	// 	}
	// }
	//
	// $taxonomy = array(
	// 	array(
	// 		'field' => 'field_tags',
	// 		'value' => $mt
	// 	),
	// 	array(
	// 		'field' => 'field_departments',
	// 		'value' => $ed
	// 	),
	// 	array(
	// 		'field' => 'field_event_tags',
	// 		'value' => $et
	// 	)
	// );
	//
	// $variables['searching'] = false;
	// $variables['search_query'] = '';
	// $q_builder = array();
	//
	// $q_category = '';
	// if (!empty($_q['category']) && is_numeric($_q['category'])) {
	// 	$q_category = explode(',', $_q['category']);
	// 	$variables['searching'] = true;
	// }
	//
	// $q_department = '';
	// if (!empty($_q['department']) && is_numeric($_q['department'])) {
	// 	$q_department = explode(',', $_q['department']);
	// 	$variables['searching'] = true;
	// }
	//
	// $q_datestart = '';
	// $q_dateend = '';
	// if (!empty($_q['start'])) {
	// 	$q_datestart = $_q['start'];
	// 	$variables['searching'] = true;
	// }
	// if (!empty($_q['end'])) {
	// 	$q_dateend = $_q['end'];
	// 	$variables['searching'] = true;
	// }
	//
	// $events = new EventsQuery($taxonomy);
	// $events->filterByEventTag($q_category);
	// $events->filterByDepartment($q_department);
	// $events->filterByDateRange($q_datestart, $q_dateend);
	//
	// $q_debug = nvl($_q, 'debug');
	//
	// $variables['events_query'] = $events;
	// $variables['events'] = $events->results(10, $variables['offset']);
	//
	// // print_r($variables['offset']);
	//
	// if (!empty($q_debug)) {
	// 	$variables['events_count'] = (int) $q_debug; //$events->count(true);
	// 	$variables['events_count_un'] = (int) $q_debug; //$events->count();
	// 	$variables['page_count'] = (int) $q_debug / 10; //$events->pageCount();
	// } else {
	// 	$variables['events_count'] = $events->count(true);
	// 	$variables['events_count_un'] = $events->count();
	// 	$variables['page_count'] = $events->pageCount();
	// }
}

function get_event_current_date($node) {

	// $dates = nv($node, 'field_date');
	// $current = array();
	//
	// if (!empty($dates)){
	// 	if (!empty($dates['start'])) {
	// 		$current = $dates;
	// 	} else {
	// 		$current = array_shift($dates);
	//
	// 		while (count($dates) > 0 && $current['diff'] < 0) {
	// 			$current = array_shift($dates);
	// 		}
	// 	}
	// }
	//
	// return $current;

}
