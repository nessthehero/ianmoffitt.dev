<!doctype html>
<!--[if lt IE 7]>      <html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html <?php language_attributes(); ?> class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>

        <meta name="description" content="<?php bloginfo('description'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- dns prefetch -->
		<link href="//www.google-analytics.com" rel="dns-prefetch">

        <!-- Google fonts -->
        <link href='http://fonts.googleapis.com/css?family=Share+Tech|Lato:400,900' rel='stylesheet' type='text/css'>

		<!-- icons
		<link href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico" rel="shortcut icon">
		<link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">
		-->

		<?php wp_head(); ?>

    </head>
    <body <?php body_class(); ?>>

        <div class="container">

            <header id="header">

                <h1><a href="<?php echo home_url(); ?>" title="Return to home page">Ian Moffitt</a></h1>
                <div class="tagline">
                    Websites. Catchphrases.<br>
                    Both.
                </div>

                <a href="#menu" class="menu-link">[Menu]</a>

                <nav id="menu" role="navigation">
                    <div class="search">
                        <?php get_template_part('searchform'); ?>
                    </div>

                    <?php nth_main_nav(); ?>

                </nav>

            </header>
