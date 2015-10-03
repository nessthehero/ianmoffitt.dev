<?php /* Template Name: Porfolio Archive */ get_header(); ?>

	<!-- main -->
	<main id="main">

		<div class="container">

			<section class="portfolio">

				<h1>Portfolio</h1>

				<?php if (have_posts()): while (have_posts()) : the_post(); ?>

					<!-- article -->
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<?php the_content(); ?>

					</article>
					<!-- /article -->

				<?php endwhile; ?>

				<?php endif; ?>

				<section class="collection">

				<?php

					$args = array(
						'numberposts' => -1,
						'post_type' => "portfolio",
						'orderby' => "portfolio_start_date",
						'order' => "ASC"
					);
					$the_query = get_posts( $args );

					// print_r($the_query);

					if ( 0 != count($the_query) ) {

				?>
					<ul>
				<?php

						foreach ( $the_query as $p ) {

							$custom = get_post_custom($p->ID);

							$img = wp_get_attachment_image($custom['portfolio_image'], 'medium');

				?>
						<li>
							<div class="image-container">
							<?php

								if ($img != '') {
									echo $img;
								} else {

									$day = substr($custom['portfolio_start_date'][0], 0, 2);
									$mon = substr($custom['portfolio_start_date'][0], 3, 5);
									$year = substr($custom['portfolio_start_date'][0], 6, 10);

							?>
								<span class="day"><?php echo $day; ?></span>
								<span class="month"><?php echo $mon; ?></span>
								<span class="year"><?php echo $year; ?></span>
							<?php

								}

							?>
							</div>
							<div class="text-container">
								<span class="url"><?php echo $custom['portfolio_url'][0]; ?></span>
								<span class="title"><?php echo $custom['portfolio_title'][0]; ?></span>
							</div>
						</li>

				<?php

							print_r($custom);
						}

	            ?>
	           		</ul>
	            <?php

					}

				?>

				</section>

			</section>

		</div>

	</main>
	<!-- /main -->

<?php get_footer(); ?>
