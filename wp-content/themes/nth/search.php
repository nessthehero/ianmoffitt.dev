<?php get_header(); ?>
	
	<!-- main -->
	<main id="main">	
		
		<h1><?php echo sprintf( __( '%s Search Results for ', 'html5blank' ), $wp_query->found_posts ); echo get_search_query(); ?></h1>

		<section class="loop">
		
			<?php get_template_part('loop'); ?>
			
			<?php get_template_part('pagination'); ?>

		</section>
	
		<?php get_sidebar(); ?>

	</main>
	<!-- /main -->	

<?php get_footer(); ?>