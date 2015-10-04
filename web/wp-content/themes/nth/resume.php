<?php /* Template Name: Resume */ get_header(); ?>

	<!-- main -->
	<main id="main">

		<div class="container">

			<section class="resume single-post">

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
						'post_type' => "job-position",
						'orderby' => "job_position_start_date",
						'order' => "ASC"
					);
					$the_query = get_posts( $args );

					// print_r($the_query);

					if ( 0 != count($the_query) ) {

						foreach ( $the_query as $p ) {

							$custom = get_post_custom($p->ID);

							$start = $custom['job_position_start_date'][0];
							$end = $custom['job_position_end_date'][0];
							if ($custom['job_position_currently'][0] == 'on') {
								$end = "Present";
							}
				?>
						<h2 class="position"><?= $custom['job_position_title'][0]; ?></h2>
						<span class="employer"><?= $custom['job_position_employer'][0]; ?></span>
						<span class="slash">/</span>
						<span class="range"><span class="from"><?= $start ?></span> - <span class="to"><?= $end; ?></span></span>
						<div class="description">
							<?= nl2br($custom['job_position_description'][0]); ?>
						</div>
				<?php

							// print_r($custom);
						}
					}

				?>

			</section>

		</div>

	</main>
	<!-- /main -->

<?php get_footer(); ?>
