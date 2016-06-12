// <?php

// function create_post_type_job_position()
// {

// 	register_post_type('job-position', // Register Custom Post Type
// 		array(
// 		'labels' => array(
// 			'name' => __('Job Position', 'nth_theme'), // Rename these to suit
// 			'singular_name' => __('Job Position', 'nth_theme'),
// 			'add_new' => __('Add New', 'nth_theme'),
// 			'add_new_item' => __('Add New Job Position', 'nth_theme'),
// 			'edit' => __('Edit', 'nth_theme'),
// 			'edit_item' => __('Edit Job Position', 'nth_theme'),
// 			'new_item' => __('New Job Position', 'nth_theme'),
// 			'view' => __('View Job Position', 'nth_theme'),
// 			'view_item' => __('View Job Position', 'nth_theme'),
// 			'search_items' => __('Search Job Positions', 'nth_theme'),
// 			'not_found' => __('No Job Positions found', 'nth_theme'),
// 			'not_found_in_trash' => __('No Job Positions found in Trash', 'nth_theme')
// 		),
// 		'description' => 'Job position that I\'ve had in the past or currently hold.',
// 		'exclude_from_search' => true,
// 		'publicly_queryable' => false,
// 		'show_in_nav_menus' => false,
// 		'public' => true,
// 		'hierarchical' => false, // Allows your posts to behave like Hierarchy Pages
// 		'has_archive' => true,
// 		'supports' => array(
// 			'title',
// 			'revisions'
// 		), // Go to Dashboard Custom Job Position Blank post for supports
// 		'can_export' => true // Allows export in Tools > Export
// 	));
// }

// // add_action('init', 'create_post_type_job_position'); // Add our Custom Post Type

// // Meta data
// function add_meta_box_job_position() {
// 	// Build meta box
// 	add_meta_box(
// 		'job_position_meta',
// 		'Job Information',
// 		'show_custom_meta_job_position',
// 		'job-position'
// 		);
// }

// add_action('add_meta_boxes', 'add_meta_box_job_position');  

// // Define fields
// $prefix = 'job_position_';
// $custom_meta_fields_job_position = array(
// 	array(
// 		'label' => 'Job Title',
// 		'desc'  => 'Your position',
// 		'id'    => $prefix.'title',
// 		'type'  => 'text'
// 		),
// 	array(
// 		'label' => 'Employer',
// 		'desc'  => 'Who you worked for',
// 		'id'    => $prefix.'employer',
// 		'type'  => 'text'
// 		),
// 	array(
// 		'label' => 'Job Description',
// 		'desc'  => 'Description of work and responsibilities',
// 		'id'    => $prefix.'description',
// 		'type'  => 'textarea'
// 		),
// 	array(
// 		'label' => 'Start Date',
// 		'desc'  => 'Date employment started',
// 		'id'    => $prefix.'start_date',
// 		'type'  => 'date'
// 		),
// 	array(
// 		'label' => 'End Date',
// 		'desc'  => 'Date employment ended',
// 		'id'    => $prefix.'end_date',
// 		'type'  => 'date'
// 		),
// 	array(
// 		'label' => 'Currently work here?',
// 		'desc'  => 'Do you currently work here?',
// 		'id'    => $prefix.'currently',
// 		'type'  => 'checkbox'
// 		)        
// 	// array(
// 	//     'label' => '',
// 	//     'desc'  => '',
// 	//     'id'    => $prefix.'',
// 	//     'type'  => ''
// 	//     ),
// 	// array(
// 	//     'label' => '',
// 	//     'desc'  => '',
// 	//     'id'    => $prefix.'',
// 	//     'type'  => ''
// 	//     ),
// 	);

// // Build display of meta box
// function show_custom_meta_job_position()
// {
// 	global $custom_meta_fields_job_position, $post;

// 	echo util_output_fields_table($custom_meta_fields_job_position, $post, wp_create_nonce(basename(__FILE__)), 'job_position_meta_box');
// }

// // Save the Data
// function save_custom_meta_job_position($post_id) {
// 	global $custom_meta_fields_job_position;
	
// 	// verify nonce
// 	if (!wp_verify_nonce($_POST['job_position_meta_box_nonce'], basename(__FILE__))) 
// 		return $post_id;
// 	// check autosave
// 	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
// 		return $post_id;
// 	// check permissions
// 	if ('page' == $_POST['post_type']) {
// 		if (!current_user_can('edit_page', $post_id))
// 			return $post_id;
// 		} elseif (!current_user_can('edit_post', $post_id)) {
// 			return $post_id;
// 	}
	
// 	// loop through fields and save the data
// 	foreach ($custom_meta_fields_job_position as $field) {
// 		$old = get_post_meta($post_id, $field['id'], true);
// 		$new = $_POST[$field['id']];
// 		if ($new && $new != $old) {
// 			update_post_meta($post_id, $field['id'], $new);
// 		} elseif ('' == $new && $old) {
// 			delete_post_meta($post_id, $field['id'], $old);
// 		}
// 	} // end foreach
// }
// add_action('save_post', 'save_custom_meta_job_position');