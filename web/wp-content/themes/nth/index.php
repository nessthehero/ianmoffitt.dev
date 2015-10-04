<?php get_header(); ?>

	<!-- main -->
	<main id="main">

		<div class="container">

			<section class="loop">

				<?php get_template_part('loop'); ?>

				<?php get_template_part('pagination'); ?>

			</section>

			<?php get_sidebar(); ?>

		</div>

	</main>
	<!-- /main -->

<?php get_footer(); ?>
