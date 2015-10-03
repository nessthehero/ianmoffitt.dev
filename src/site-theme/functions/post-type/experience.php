<?php

function create_post_type_experience()
{
    register_post_type('experience', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('Experience', 'nth_theme'), // Rename these to suit
            'singular_name' => __('Experience', 'nth_theme'),
            'add_new' => __('Add New', 'nth_theme'),
            'add_new_item' => __('Add New Experience', 'nth_theme'),
            'edit' => __('Edit', 'nth_theme'),
            'edit_item' => __('Edit Experience', 'nth_theme'),
            'new_item' => __('New Experience', 'nth_theme'),
            'view' => __('View Experience', 'nth_theme'),
            'view_item' => __('View Experience', 'nth_theme'),
            'search_items' => __('Search Experience', 'nth_theme'),
            'not_found' => __('No Experience found', 'nth_theme'),
            'not_found_in_trash' => __('No Experience found in Trash', 'nth_theme')
        ),
        'public' => true,
        'hierarchical' => false, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
            'title'
        ), // Go to Dashboard Custom experience Blank post for supports
        'can_export' => true // Allows export in Tools > Export
    ));
}

add_action('init', 'create_post_type_experience'); // Add our HTML5 Blank Custom Post Type

// Meta data
function add_meta_box_experience() {
    // Build meta box
    add_meta_box(
        'experience_meta',
        'Experience - Additional Information',
        'show_custom_meta_experience',
        'experience'
    );
}

add_action('add_meta_boxes', 'add_meta_box_experience');  

// Define fields
$prefix = 'experience_';
$custom_meta_fields_experience = array();

$custom_meta_fields_portfolio[] =  array(
    'label' => 'Experience',
    'desc'  => 'Name of technology, tool, or concept',
    'id'    => $prefix.'title',
    'type'  => 'text'
);

$custom_meta_fields_portfolio[] =  array(
    'label'   => 'Level of experience',
    'desc'    => 'Your level of experience',
    'id'      => $prefix.'level',
    'type'    => 'select',
    'options' => array(
        'Beginner',
        'Intermediate',
        'Expert'
    )
);

$custom_meta_fields_portfolio[] =  array(
    'label'   => 'Frequency of Use',
    'desc'    => 'How often you use the technology, tool, or concept',
    'id'      => $prefix.'level',
    'type'    => 'select',
    'options' => array(
        'Beginner',
        'Intermediate',
        'Expert'
    )
);

$custom_meta_fields_portfolio[] =  array(
    'label' => 'Date Started',
    'desc'  => 'Date project kicked off',
    'id'    => $prefix.'start_date',
    'type'  => 'date'
);

$custom_meta_fields_portfolio[] =  array(
    'label' => 'Date Completed',
    'desc'  => 'Date project launched or work was completed',
    'id'    => $prefix.'end_date',
    'type'  => 'date'
);