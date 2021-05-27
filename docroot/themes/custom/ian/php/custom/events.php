<?php

	use \Drupal\node\Entity\Node;

	class EventsQuery
	{

		protected $posts = array();
		protected $rawPosts = array();
		protected $events = array();
		protected $rawEvents = array();
		protected $taxonomy = array();
		protected $sort = 'date';
		protected $mode = 'nonstrict';
		protected $promoted = false;
		protected $eventOrder = 'default';

		const MAX_POSTS = 4;
		const PAGE_SIZE = 10;

		public function __construct($taxonomy, $start_date = '', $end_date = '', $sort = 'date', $mode = 'nonstrict')
		{

			$this->taxonomy = array_filter($taxonomy);
			$this->sort = $sort;
			$this->mode = $mode;

			if ($start_date !== '') {
				$this->start_date_filter = $start_date;
				$this->start_date = gmdate('Y-m-dT00:00:00', strtotime($start_date));
			}

			if ($end_date !== '') {
				$this->end_date_filter = $end_date;
				$this->end_date = gmdate('Y-m-dT00:00:00', strtotime($end_date));
			}

			$this->filter();

		}

		protected function filter($reset = false)
		{

			if ($reset === true) {
				$this->posts = array();
				$this->rawPosts = array();
				$this->events = array();
				$this->rawEvents = array();
			}

			$cached = '';

			$cache_tax = '';
			if (!empty($this->taxonomy) && is_array($this->taxonomy)) {
				foreach ($this->taxonomy as $filter) {
					$cache_tax .= '-' . implode('-', $filter['value']);
				}
			}

			$cache_key = "events-" . date("Y-m-d-h", time()) . $cache_tax;

			if ($this->promoted === true) {
				$cache_key = "promoted-" . $cache_key;
			}

			if ($this->eventOrder == 'reverse') {
				$cache_key = "reverse-" . $cache_key;
			}

			$cache_time = '+1 hours';
			$expire = strtotime($cache_time, time());

			if ($cached = \Drupal::cache()->get($cache_key)) {
				if (isset($cached->data) && !empty($cached->data)) {
					$this->posts = $cached->data;
				}
			}

			if (count($this->posts) == 0) {

				$query = \Drupal::entityQuery('node');

				$query->condition('type', 'event');

				$query->condition('status', 1);

				if ($this->promoted === true) {
					$query->condition('promote', '0', '!=');
				}

				if (!empty($this->taxonomy) && is_array($this->taxonomy)) {
					foreach ($this->taxonomy as $filter) {
						if (!empty($filter['value'])) {
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

				\Drupal::cache()->set($cache_key, $this->posts, $expire);

			}

			$this->organizeEvents();

			$this->rawPosts = $this->posts;
			$this->rawEvents = $this->events;

		}

		private function _load_data($nid)
		{

			$node = Node::load($nid);
			$return = array();

			if ($node->hasField('field_date')) {

				$title = $node->title->value;
				if ($node->hasField('field_heading') && !$node->field_heading->isEmpty()) {
					$title = $node->field_heading->value;
				}

				$event_categories = array();
				if ($node->hasField('field_event_categories') && !$node->field_event_categories->isEmpty()) {
					$_event_categories = getTagsArray($node->get('field_event_categories'));
					if (!empty($_event_categories)) {
						$event_categories = array_merge(array(), (array) $_event_categories);
					}
				}

				$start = '';
				$end = '';
				$start_formatted = '';
				$end_formatted = '';

				if ($node->hasField('field_date') && !$node->field_date->isEmpty()) {
					$dateobj = date_recur__dateobj($node->field_date);

					if (!empty($dateobj)) {

						$start = $dateobj['next']['start']; //$node->field_date->start_date->getTimestamp();
						$end = $dateobj['next']['end']; //$node->field_date->end_date->getTimestamp();
						$start_formatted = parse_timestamp($start, 'Y-m-d\TH:i:sP');
						$end_formatted = parse_timestamp($end, 'Y-m-d\TH:i:sP');

					}
				}

				$return = array(
					'nid'                    => $nid,
					'promote'                => $node->get('promote')->value,
					'sticky'                 => $node->isSticky(),
					'published'              => $node->isPublished(),
					'title'                  => $title,
					'start'                  => $start,
					'end'                    => $end,
					'start_formatted'        => $start_formatted,
					'end_formatted'          => $end_formatted,
					'field_event_categories' => $event_categories
				);

			}

			return $return;

		}

		public function resetFiltering()
		{

			$this->posts = $this->rawPosts;
			$this->rawEvents = $this->events;

		}

		public function results($amt = 10, $offset = 0)
		{

			if ($amt == -1) {
				return $this->events;
			} else {
				$slice = array_slice($this->events, ($offset * $amt), $amt);

				return $slice;
			}

		}

		public function remove($items)
		{

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

		protected function organizeEvents()
		{

			$event_sort_by_date = array();
			$event_date_col = array();
			$sticky_events = array();

			$older_events = array();
			$older_events_date_col = array();

			foreach ($this->posts as $j => $p) {

				if (!empty($p['start'])) {

					$rawstart = $p['start'];
					$rawend = $p['end'];
					$now = \Drupal::time()->getCurrentTime();

					$alias = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $p['nid']);

					// Future events and events happening now.
					if ($rawstart >= $now || $rawend >= $now) {

						if (empty($p['sticky'])) {

							$event_sort_by_date[] = array(
								'date'                   => $rawstart,
								'promote'                => $p['promote'],
								'published'              => $p['published'],
								'title'                  => $p['title'],
								'url'                    => $alias,
								'nid'                    => $p['nid'],
								'start'                  => $p['start'],
								'end'                    => $p['end'],
								'start_formatted'        => $p['start_formatted'],
								'end_formatted'          => $p['end_formatted'],
								'field_event_categories' => $p['field_event_categories']
							);

							$event_date_col[] = $rawstart;

						} else {

							$sticky_events[] = array(
								'date'                   => $rawstart,
								'promote'                => $p['promote'],
								'title'                  => $p['title'],
								'url'                    => $alias,
								'nid'                    => $p['nid'],
								'start'                  => $p['start'],
								'end'                    => $p['end'],
								'start_formatted'        => $p['start_formatted'],
								'end_formatted'          => $p['end_formatted'],
								'field_event_categories' => $p['field_event_categories']
							);

						}

						// Past events
					} else {

						$older_events[] = array(
							'date'                   => $rawstart,
							'promote'                => $p['promote'],
							'published'              => $p['published'],
							'title'                  => $p['title'],
							'url'                    => $alias,
							'nid'                    => $p['nid'],
							'start'                  => $p['start'],
							'end'                    => $p['end'],
							'start_formatted'        => $p['start_formatted'],
							'end_formatted'          => $p['end_formatted'],
							'field_event_categories' => $p['field_event_categories']
						);

						$older_events_date_col[] = $rawstart;

					}

				}

			}

			if ($this->eventOrder == 'reverse') {
				array_multisort($event_date_col, SORT_DESC, $event_sort_by_date);
				array_multisort($older_events_date_col, SORT_ASC, $older_events);
			} else {
				array_multisort($event_date_col, SORT_ASC, $event_sort_by_date);
				array_multisort($older_events_date_col, SORT_DESC, $older_events);
			}

			$this->events = array_merge($sticky_events, $event_sort_by_date, $older_events);

		}

		public function getOnlyPromoted()
		{

			$this->promoted = true;

			$this->filter(true);

		}

		public function reverse()
		{

			if ($this->eventOrder !== 'reverse') {
				$this->eventOrder = 'reverse';
			} else {
				$this->eventOrder = 'default';
			}

			$this->organizeEvents();

		}

		public function filterByTaxonomy($field, $tid_array)
		{

			if (!empty($tid_array) && is_array($tid_array)) {
				foreach ($this->posts as $j => $p) {

					if (!empty($p[$field])) {

						if (empty(array_intersect($p[$field], $tid_array))) {
							unset($this->posts[$j]);
						}

					} else {
						unset($this->posts[$j]);
					}

				}
				$this->organizeEvents();
			}

		}

		public function filterByDateRange($start, $end)
		{

			if ($start != '' || $end != '') {
				foreach ($this->posts as $j => $p) {

					if ($start != '') {

						$startraw = DateTime::createFromFormat('m/d/Y', $start)->format('U');
						$eventstartraw = $p['start'];

						if ($eventstartraw >= $startraw) {

							// You match! Do nothing!

						} else {

							unset($this->posts[$j]);

						}
					}

					if ($end != '') {

						$endraw = DateTime::createFromFormat('m/d/Y', $end)->format('U');
						$eventendraw = $p['end'];

						if ($eventendraw <= $endraw) {

							// You match! Do nothing!

						} else {

							unset($this->posts[$j]);

						}

					}

				}

				$this->organizeEvents();
			}

		}

		public function filterByDay($day)
		{

			if ($day != '') {

				$start = DateTime::createFromFormat('m/d/Y', $day);
				$startraw = $start->setTime(0, 0, 0, 0)->format('U');
				$endraw = $start->setTime(24, 59, 59, 999)->format('U');

				foreach ($this->posts as $j => $p) {

					$eventstartraw = $p['start'];

					if ($eventstartraw < $startraw) {
						unset($this->posts[$j]);
					}

					if ($eventstartraw > $endraw) {
						unset($this->posts[$j]);
					}

				}

				$this->organizeEvents();
			}

		}

		public function count($filtered = false)
		{

			if ($filtered) {
				return count($this->events);
			} else {
				return count($this->rawPosts);
			}

		}

		public function pageCount($amt = 10)
		{

			return ceil(count($this->events) / $amt);

		}

	}

	function build_events_search($node, &$variables, &$_q)
	{

		$offset = 0;
		if (!empty($_q['page']) && is_numeric($_q['page'])) {
			$offset = $_q['page'] - 1;
		}

		$variables['offset'] = $offset;
		$variables['prev_page'] = $offset;
		$variables['current_page'] = $offset + 1;
		$variables['next_page'] = $offset + 2;

		$event_categories = getTermsFromField($node, 'field_event_categories');
		$taxonomy = array(
			getTaxonomyFinderArray('field_event_categories', $event_categories['tids'])
		);

		$q_category = '';
		if (!empty($_q['category']) && is_numeric($_q['category'])) {
			$q_category = explode(',', $_q['category']);
		}

		$q_day = '';
		if (!empty($_q['day'])) {
			$q_day = $_q['day'];
		}

		$events = new EventsQuery($taxonomy);
		$events->filterByTaxonomy('field_event_categories', $q_category);
		$events->filterByDay($q_day);

		$q_debug = nvl($_q, 'debug');

		$variables['events_query'] = $events;

		$count = 10;

		$variables['events_results'] = $events->results($count, $variables['offset']);
		$variables['events_all_results'] = $events->results(-1);
		$variables['events'] = load_nodes($variables['events_results'], 'result');

		if (!empty($q_debug)) {
			$variables['events_count'] = (int) $q_debug; //$events->count(true);
			$variables['events_count_un'] = (int) $q_debug; //$events->count();
			$variables['page_count'] = (int) $q_debug / $count; //$events->pageCount();
		} else {
			$variables['events_count'] = $events->count(true);
			$variables['events_count_un'] = $events->count();
			$variables['page_count'] = $events->pageCount($count);
		}

		$variables['pagination'] = pagination($node, $_q, $offset + 1, $variables['page_count']);

		$variables['attributes']['data-total'] = count($variables['events']); //$news->count(true); //$variables['news_count'];

	}
