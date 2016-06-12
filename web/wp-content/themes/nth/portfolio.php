<?php /* Template Name: Porfolio Archive */ get_header(); ?>

	<!-- main -->
	<main id="main">

		<div class="container">

			<section class="portfolio">

				<h1><?php the_title(); ?></h1>

				<?php if (have_posts()): while (have_posts()) : the_post(); ?>

					<!-- article -->
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<?php the_content(); ?>

					</article>
					<!-- /article -->

				<?php endwhile; ?>

				<?php endif; ?>

				<?php

					$args = array(
						'numberposts' => -1,
						'post_type' => "portfolio",
						'orderby' => "portfolio_start_date",
						'order' => "ASC"
					);
					$the_query = get_posts( $args );

					if ( 0 != count($the_query) ) {

						foreach ( $the_query as $p ) {

							$custom = get_post_custom($p->ID);

							print_r($custom);
						}
					}

				?>

			</section>

		</div>

	</main>
	<!-- /main -->

<?php get_footer(); ?>
