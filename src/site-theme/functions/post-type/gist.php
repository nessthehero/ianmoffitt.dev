<?php

function create_post_type_portfolio()
{
    register_taxonomy_for_object_type('category', 'portfolio'); // Register Taxonomies for Category
    register_taxonomy_for_object_type('post_tag', 'portfolio');
    register_post_type('portfolio', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('Portfolio Item', 'nth_theme'), // Rename these to suit
            'singular_name' => __('Portfolio Item', 'nth_theme'),
            'add_new' => __('Add New', 'nth_theme'),
            'add_new_item' => __('Add New Portfolio Item', 'nth_theme'),
            'edit' => __('Edit', 'nth_theme'),
            'edit_item' => __('Edit Portfolio Item', 'nth_theme'),
            'new_item' => __('New Portfolio Item', 'nth_theme'),
            'view' => __('View Portfolio Item', 'nth_theme'),
            'view_item' => __('View Portfolio Item', 'nth_theme'),
            'search_items' => __('Search Portfolio Item', 'nth_theme'),
            'not_found' => __('No Portfolio Items found', 'nth_theme'),
            'not_found_in_trash' => __('No Portfolio Items found in Trash', 'nth_theme')
        ),
        'public' => true,
        'hierarchical' => false, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'thumbnail',
            'custom-fields',
            'revisions',
            'page-attributes'
        ), // Go to Dashboard Custom portfolio Blank post for supports
        'can_export' => true, // Allows export in Tools > Export
        'taxonomies' => array(
            'post_tag',
            'category'
        ) // Add Category and Post Tags support
    ));
}

add_action('init', 'create_post_type_portfolio'); // Add our HTML5 Blank Custom Post Type