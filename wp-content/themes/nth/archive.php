<?php get_header(); ?>

	<!-- main -->
	<main id="main">

		<!-- section -->
		<section class="archive" role="main">

			<h1><?php _e( 'Archives', 'html5blank' ); ?></h1>

			<?php get_template_part('loop'); ?>

			<?php get_template_part('pagination'); ?>

		</section>
		<!-- /section -->

		<?php get_sidebar(); ?>

	</main>

<?php get_footer(); ?>
