<?php

	function taxonomy_technology_init() {
		// create a new taxonomy
		register_taxonomy(
			'technology',
			'portfolio',
			array(
				'label' => __( 'Technology' ),
				'rewrite' => array( 'slug' => 'built-with' )				
			)
		);
	}
	add_action( 'init', 'taxonomy_technology_init' );