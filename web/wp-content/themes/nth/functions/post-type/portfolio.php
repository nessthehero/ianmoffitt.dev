// <?php

// function create_post_type_portfolio()
// {
//     register_taxonomy_for_object_type('category', 'portfolio'); // Register Taxonomies for Category
//     // register_taxonomy_for_object_type('post_tag', 'portfolio');
//     register_taxonomy_for_object_type('technology', 'portfolio');
//     register_post_type('portfolio', // Register Custom Post Type
//         array(
//         'labels' => array(
//             'name' => __('Portfolio Item', 'nth_theme'), // Rename these to suit
//             'singular_name' => __('Portfolio Item', 'nth_theme'),
//             'add_new' => __('Add New', 'nth_theme'),
//             'add_new_item' => __('Add New Portfolio Item', 'nth_theme'),
//             'edit' => __('Edit', 'nth_theme'),
//             'edit_item' => __('Edit Portfolio Item', 'nth_theme'),
//             'new_item' => __('New Portfolio Item', 'nth_theme'),
//             'view' => __('View Portfolio Item', 'nth_theme'),
//             'view_item' => __('View Portfolio Item', 'nth_theme'),
//             'search_items' => __('Search Portfolio Item', 'nth_theme'),
//             'not_found' => __('No Portfolio Items found', 'nth_theme'),
//             'not_found_in_trash' => __('No Portfolio Items found in Trash', 'nth_theme')
//         ),
//         'public' => true,
//         'hierarchical' => false, // Allows your posts to behave like Hierarchy Pages
//         'has_archive' => true,
//         'supports' => array(
//             'title',
//             'editor',
//             'revisions'
//         ), // Go to Dashboard Custom portfolio Blank post for supports
//         'can_export' => true, // Allows export in Tools > Export
//         'taxonomies' => array(
//             // 'post_tag',
//             'category',
//             'technology'
//         ) // Add Category and Post Tags support
//     ));
// }

// // add_action('init', 'create_post_type_portfolio'); // Add our HTML5 Blank Custom Post Type

// // Meta data
// function add_meta_box_portfolio() {
//     // Build meta box
//     add_meta_box(
//         'portfolio_meta',
//         'Portfolio Piece Additional Information',
//         'show_custom_meta_portfolio',
//         'portfolio'
//         );
// }

// // add_action('add_meta_boxes', 'add_meta_box_portfolio');

// // Define fields
// $prefix = 'portfolio_';
// $custom_meta_fields_portfolio = array();

// $custom_meta_fields_portfolio[] =  array(
//     'label' => 'Project Title',
//     'desc'  => 'Name of project/piece',
//     'id'    => $prefix.'title',
//     'type'  => 'text'
// );

// $custom_meta_fields_portfolio[] =  array(
//     'label' => 'Project URL',
//     'desc'  => 'URL of project/piece',
//     'id'    => $prefix.'url',
//     'type'  => 'text'
// );

// $custom_meta_fields_portfolio[] =  array(
//     'label' => 'Description',
//     'desc'  => 'Describe the project',
//     'id'    => $prefix.'description',
//     'type'  => 'textarea'
// );

// $custom_meta_fields_portfolio[] =  array(
//     'label' => 'Date Started',
//     'desc'  => 'Date project kicked off',
//     'id'    => $prefix.'start_date',
//     'type'  => 'date'
// );

// $custom_meta_fields_portfolio[] =  array(
//     'label' => 'Date Completed',
//     'desc'  => 'Date project launched or work was completed',
//     'id'    => $prefix.'end_date',
//     'type'  => 'date'
// );

// if (wp_count_posts('job-position')->publish > 0) {

//     $custom_meta_fields_portfolio[] =  array(
//         'label' => 'Job created at',
//         'desc'  => 'Your position at the time you created this piece',
//         'id'    => $prefix.'job_position',
//         'type'  => 'post',
//         'post-type' => 'job-position'
//     );

// }

// $custom_meta_fields_portfolio[] =  array(
//     'label'  => 'Screenshot',
//     'desc'  => 'Screenshot of portfolio piece',
//     'id'    => $prefix.'image',
//     'type'  => 'image'
// );

// // array_push(
// //     array(
// //          'label' => '',
// //          'desc'  => '',
// //          'id'    => $prefix.'',
// //          'type'  => ''
// //     ),
// // , $custom_meta_fields_portfolio);

// // Build display of meta box
// function show_custom_meta_portfolio()
// {
//     global $custom_meta_fields_portfolio, $post;

//     echo util_output_fields_table($custom_meta_fields_portfolio, $post, wp_create_nonce(basename(__FILE__)), 'portfolio_meta_box');

// }

// // Save the Data
// function save_custom_meta_portfolio($post_id) {
//     global $custom_meta_fields_portfolio;

//     // verify nonce
//     if (!wp_verify_nonce($_POST['portfolio_meta_box_nonce'], basename(__FILE__)))
//         return $post_id;
//     // check autosave
//     if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
//         return $post_id;
//     // check permissions
//     if ('page' == $_POST['post_type']) {
//         if (!current_user_can('edit_page', $post_id))
//             return $post_id;
//         } elseif (!current_user_can('edit_post', $post_id)) {
//             return $post_id;
//     }

//     // loop through fields and save the data
//     foreach ($custom_meta_fields_portfolio as $field) {
//         $old = get_post_meta($post_id, $field['id'], true);
//         $new = $_POST[$field['id']];
//         if ($new && $new != $old) {
//             update_post_meta($post_id, $field['id'], $new);
//         } elseif ('' == $new && $old) {
//             delete_post_meta($post_id, $field['id'], $old);
//         }
//     } // end foreach
// }
// add_action('save_post', 'save_custom_meta_portfolio');
