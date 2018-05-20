<?php

/**
 * Returns field value from entity. Can be an array or single value.
 * Returns whole object of field, not just safe_value
 *
 * @param  Drupal Entity $entity  Entity we are grabbing field from
 * @param  string $field Name of field ID
 * @param  string $mode  view mode (optional)
 *
 * @return array        Array of fields or field array
 */
function v($entity, $entity_type, $field, $mode = 'full') {

	if (!empty($entity)) {

        $items = field_get_items($entity_type, $entity, $field);

		// Get field type
        $f = field_info_field($field);
        $type = $f['type'];

        if ($field == 'field_featured_pages') {
            // print_r($items);
            // print_r(count($items));
            // print_r($f);
            // if (is_array($items)) { print_r("yes"); } else { print_r("no"); }
        }

        // print_r(var_export($items));

        return parse_field($field, $items, $entity, $entity_type, $type, $mode);

    } else {
        return "";
    }

}

/**
 * Returns field value from taxonomy term. Can be an array or single value.
 * Returns whole object of field, not just safe_value
 *
 * @param  Drupal Term $term  Term entity we are grabbing field from
 * @param  string $field Name of field ID
 * @param  string $mode  view mode (optional)
 *
 * @return array        Array of fields or field array
 */
function tv($term, $field, $mode = 'full') {
	return v($term, "taxonomy_term", $field, $mode);
}

/**
 * Returns field value from node. Can be an array or single value.
 * Returns whole object of field, not just safe_value
 *
 * @param  Drupal Node $node  Node entity we are grabbing field from
 * @param  string $field Name of field ID
 * @param  string $mode  view mode (optional)
 *
 * @return array        Array of fields or field array
 */
function nv($node, $field, $mode = 'full') {
	return v($node, "node", $field, $mode);
}

/**
 * Parses field and returns a cleaned array
 *
 * @param  string $field       Field name
 * @param  array $items        array of field values
 * @param  object $entity      original entity that field is from
 * @param  string $entity_type type of entity
 * @param  string $type        Field type
 * @param  string $mode        View mode
 * @return array               Output
 */
function parse_field($field, $items, $entity, $entity_type = 'node', $type = '', $mode = 'full') {

	$output = array();

	$lang = !empty($entity->language) ? $entity->language : nvl(language_default("language"), 'und');

	if (is_array($items) && count($items) > 0) {

		foreach ($items as $value) {

			// Get the view mode of this field
			$field_view = field_view_value($entity_type, $entity, $field, $value, $mode);

			if ($field == 'field_featured_pages') {
				// print_r($entity);
			}

			// Determine output based on field type
			switch ($type) {

				// For booleans, we only need the value, since there is no reason to render anything
				case 'list_boolean':
					$output[] = parse_list_boolean($value);
					break;

				// Pass the view to parse text so we can render it with display options, like truncation
				case 'text':
					$output[] = parse_text($field_view);
					break;

				case 'number_integer':
					$output[] = drupal_render($field_view);
					break;

				case 'link_field':
					$output[] = parse_link_field($value);
					break;

				// Get a really awesome object
				case 'image':
					$output[] = parse_image($value);
					break;

				case 'file':
					$output[] = parse_file($value);
					break;

				case 'date':
				case 'datetime':
					$output[] = parse_date($value);
					break;

				case 'list_text':
					$output[] = $value['value'];
					break;

				case 'node_reference':
					if (isset($value['node'])) {
						$output[] = node_view($value['node'], $mode);
					} elseif (isset($value['nid'])) {
						$nl = node_load($value['nid']);
						if (!empty($nl)) {
							$output[] = node_view(node_load($value['nid']), $mode);
						}
					}
					break;

				case 'revisionreference':
					if (isset($value['vid'])) {
						$node = node_load(NULL, $value['vid']);
						if (isset($node->nid)) {
							$output[] = node_view($node, $mode);
						}
					}
					break;

				case 'taxonomy_term_reference':
					$output[] = $value;
					break;

				case 'field_collection':
					$output[] = parse_field_collection_item($field, array($value['value']), $lang, $mode);
					break;

				default:
					$output[] = nvl(field_view_value($entity_type, $entity, $field, $value, $mode, $lang), array());
					break;

			}

		}

	}

	if (count($output) == 1) {
		return array_pop($output);
	} else {
		return $output;
	}

}

/**
 * Parse a Field Collection. If we know what it is, we extract what we want. If not, we just get all the values we can.
 *
 * @param  string $key          Field collection key name
 * @param  array $entity_array  The entity object in an array
 * @param  string $lang         object language
 * @return array                array of clean values
 */
function parse_field_collection($key, $entity_array, $lang) {

	$output = [];

	$o_entity_array = $entity_array;
	$entity = array_shift($entity_array);

	switch ($key) {

		case 'field_impact_stories':

			from_entity_push_to_output($output, $entity, $lang, 'image', 		'field_image', 			'image');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_label', 			'label');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_heading', 		'heading');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_teaser', 		'teaser');
			// from_entity_push_to_output($output, $entity, $lang, 'link', 		'field_read_more_url',	'readmore');
			from_entity_push_to_output($output, $entity, $lang, 'list_text',	'field_impact_feature', 'feature');
			from_entity_push_to_output($output, $entity, $lang, 'link', 		'field_call_to_action',	'cta');
			from_entity_push_to_output($output, $entity, $lang, 'boolean', 		'field_is_video', 		'is_video');

			break;

		case 'field_opportunity_tiles':

			from_entity_push_to_output($output, $entity, $lang, 'image', 		'field_image', 				'image');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_heading', 			'heading');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_teaser', 			'teaser');
			from_entity_push_to_output($output, $entity, $lang, 'link', 		'field_opportunity_ctas',	'links');
			from_entity_push_to_output($output, $entity, $lang, 'boolean', 		'field_show_globe_icon',	'showglobe');

			break;

		case 'field_mym_links':

			from_entity_push_to_output($output, $entity, $lang, 'list_text', 	'field_icon', 				'icon');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_heading', 			'heading');
			from_entity_push_to_output($output, $entity, $lang, 'link', 		'field_link', 				'link');

			break;

		case 'field_masthead':

			from_entity_push_to_output($output, $entity, $lang, 'image', 		'field_image', 				'image');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_caption', 			'caption');
			from_entity_push_to_output($output, $entity, $lang, 'link', 		'field_link', 				'link');

			break;

		case 'field_accordion_items':

			from_entity_push_to_output($output, $entity, $lang, 'boolean', 		'field_open_by_default',	'is_open');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_heading', 			'heading');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_copy', 				'copy');
			from_entity_push_to_output($output, $entity, $lang, 'link', 		'field_link', 				'link');

			break;

		case 'field_list_items':

			from_entity_push_to_output($output, $entity, $lang, 'image', 		'field_image', 			'image');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_heading', 		'heading');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_teaser', 		'teaser');
			from_entity_push_to_output($output, $entity, $lang, 'link', 		'field_link', 			'link');

			break;

		case 'field_stats':

			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_number', 		'number');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_suffix', 		'suffix');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_teaser', 		'teaser');

			break;

		case 'field_media_item':

			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_media_heading', 		'heading');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_caption', 			'caption');
			from_entity_push_to_output($output, $entity, $lang, 'image', 		'field_image', 				'image');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_youtube_id', 		'youtube');
			from_entity_push_to_output($output, $entity, $lang, 'link', 		'field_cta_link', 			'link');

			break;

		case 'field_featured_pages':

			from_entity_push_to_output($output, $entity, $lang, 'node', 		'field_node', 			'npage');
			from_entity_push_to_output($output, $entity, $lang, 'image', 		'field_image', 			'image');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_heading', 		'heading');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_teaser', 		'teaser');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_link_text', 		'linktext');
			from_entity_push_to_output($output, $entity, $lang, 'link', 		'field_external_link', 	'link');

			break;

		case 'field_faculty_highlights':

			from_entity_push_to_output($output, $entity, $lang, 'text', 			'field_heading', 			'heading');
			from_entity_push_to_output($output, $entity, $lang, 'text_as_array', 	'field_highlight_items', 	'items');

			break;

		case 'field_faculty_featured_content':

			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_heading', 		'heading');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_copy', 			'copy');

			break;

		case 'field_faculty_resource_accordion':

			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_heading', 		'heading');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_copy', 			'copy');
			from_entity_push_to_output($output, $entity, $lang, 'link', 		'field_link', 			'link');

			break;

		case 'field_additional_contacts':

			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_contact_name', 		'name');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_contact_department', 'department');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_office', 			'office');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_address', 			'address');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_address_2', 			'address2');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_city', 				'city');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_state', 				'state');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_zip', 				'zip');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_country', 			'country');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_phone', 				'phone');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_fax', 				'fax');
			from_entity_push_to_output($output, $entity, $lang, 'text', 		'field_email', 				'email');
			from_entity_push_to_output($output, $entity, $lang, 'link', 		'field_facebook', 			'facebook');
			from_entity_push_to_output($output, $entity, $lang, 'link', 		'field_twitter', 			'twitter');
			from_entity_push_to_output($output, $entity, $lang, 'link', 		'field_instagram', 			'instagram');
			from_entity_push_to_output($output, $entity, $lang, 'link', 		'field_youtube', 			'youtube');

			break;

		default:

			if (!empty($entity)) {

				$obj = array_shift($entity);

				foreach ($obj as $okey => $oval) {
					$output[$okey] = $oval;
				}

			}

			break;

	}

	return $output;

}

function from_entity_push_to_output(&$output, $entity, $lang, $type, $prop, $dest, $mode = 'full') {

	if ($prop == 'field_limit') {
		// print_r($entity->{$prop}[$lang]);
	}

	if (!empty($entity->{$prop}[$lang][0])) {

		$e_arr =  $entity->{$prop}[$lang];

		switch ($type) {
			case 'boolean':
				$val = $e_arr[0];

				$output[$dest] = $val['value'];
				break;

			case 'image':
				$val = $e_arr[0];

				$output[$dest] = parse_image($val);
				break;

			case 'link':
				foreach ($e_arr as $ea) {
					$output[$dest][] = parse_link_field($ea);
				}
				break;

			case 'tname':
				$o = array();
				foreach ($entity->{$prop}[$lang] as $key => $term) {
					$name = $term['taxonomy_term'];
					$o[] = $name->name;
				}
				$output[$dest] = implode(', ', $o);
				break;

			case 'email':
				$val = $e_arr[0];

				$output[$dest] = parse_email($val);
				break;

			case 'list_text':
				$val = $e_arr[0];

				$output[$dest] = $val['value'];
				break;

			case 'node':
				$val = $e_arr[0];

				if (isset($val['nid'])) {
					$vnode = node_load($val['nid']);
					if (!empty($vnode)) {
						$output[$dest] = node_view($vnode, $mode);
					}
				}
				break;

			case 'collection':

				foreach ($e_arr as $ea) {
					$output[$dest][] = parse_field_collection_item($prop, array($ea['value']), $lang, $mode);
				}
				break;

			case 'text_as_array':

				$o = array();
				foreach ($e_arr as $key => $v) {
					$o[] = parse_plain_text($v);
				}

				$output[$dest] = $o;
				break;

			case 'text':
			default:

				$formatted = field_view_field('field_collection_item', $entity, $prop);

				if (!empty($formatted[0])) {
					$output[$dest] = drupal_render($formatted[0]);
				} else {
					$output[$dest] = parse_text($val);
				}

				break;
		}

	}

}

function parse_field_collection_item($field, $key, $lang) {

	$entity = entity_load('field_collection_item', $key);

	return parse_field_collection($field, $entity, $lang);

}

function parse_text($object) {

	// return nvl(
	// 	nvl($object, 'safe_value'),
	// 	nvl($object, 'value')
	// );

	return drupal_render($object);

}

function parse_plain_text($object) {

	$value = nvl(
		nvl($object, 'safe_value'),
		nvl($object, 'value')
	);

	$value = htmlspecialchars_decode($value);

	return $value;

	// return drupal_render($object);

}

function parse_list_boolean($object) {

	return $object['value'];

}

function parse_link_field($object) {

	$link = $object['title'];
	if (!empty($object['url'])) {
		$link = l(
			htmlspecialchars_decode($object['title']),
			$object['url'],
			array('attributes' => $object['attributes'])
		);
	}

	$return = array(
		'o' => $object,
		'l' => $link
	);

	$return['o']['safe_title'] = strip_tags($return['o']['title']);

	return $return;

}

function parse_image($object) {

	if (!empty($object['uri'])) {

		return array(
	/* object */    'o' => $object,
	/* URL */       'u' => file_create_url($object['uri']),
	/* Markup */    'm' => removehw(image($object['uri'], $object['alt'], $object['title']))
		);

	} else {
		return array();
	}

}

function parse_file($object) {
	if (!empty($object['uri'])) {

		return array(
	/* object */    'o' => $object,
	/* URL */       'u' => file_create_url($object['uri'])
		);

	} else {
		return array();
	}
}

function parse_date($object) {

	$events = array();
	$now = new DateObject();

	if (!empty($object['value']) && !empty($object['value2'])) {

		$start = new DateObject($object['value'], new DateTimeZone($object['timezone_db']));
		$end = new DateObject($object['value2'], new DateTimeZone($object['timezone_db']));

		$start->setTimezone(new DateTimeZone($object['timezone']));
		$end->setTimezone(new DateTimeZone($object['timezone']));

		$sd = $start->format('Y-m-d H:i:s');
		$sday = $start->format('Y-m-d');
		$stime = $start->format('H:i:s');
		$ed = $end->format('Y-m-d H:i:s');
		$eday = $end->format('Y-m-d');
		$etime = $end->format('H:i:s');

		$events = array(
			'start' => $sd,
			'end' => $ed,
			'start_date' => $sday,
			'end_date' => $eday,
			'start_time' => $stime,
			'end_time' => $etime,
			'allday' => (date_is_all_day($sd, $ed, 'second')) ? 1 : 0,
			'diff' => $now->difference($start, 'seconds', false)
		);

	}

	return $events;

}

function parse_email($object) {

	return nvl($object, 'email');

}
