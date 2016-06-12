<?php get_header(); ?>

	<!-- main -->
	<main id="main">

		<div class="container">

			<section class="category-listing">

				<h1><?php _e( 'Categories for', 'html5blank' ); the_category(); ?></h1>

				<?php get_template_part('loop'); ?>

				<?php get_template_part('pagination'); ?>

			</section>

			<?php get_sidebar(); ?>

		</div>

	</main>
	<!-- /main -->

<?php get_footer(); ?>
