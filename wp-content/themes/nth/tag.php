<?php get_header(); ?>
	
	<!-- main -->
	<main id="main">

		<!-- section -->
		<section class="single-page">
	
			<h1><?php _e( 'Tag Archive: ', 'html5blank' ); echo single_tag_title('', false); ?></h1>
		
			<?php get_template_part('loop'); ?>
			
			<?php get_template_part('pagination'); ?>
	
		</section>
		<!-- /section -->

		<?php get_sidebar(); ?>

	</main>
	<!-- /main -->

<?php get_footer(); ?>