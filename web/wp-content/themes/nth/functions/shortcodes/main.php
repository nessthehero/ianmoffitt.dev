<?php

// Shortcode Demo with Nested Capability
function html5_shortcode_demo($atts, $content = null)
{
    return '<div class="shortcode-demo">' . do_shortcode($content) . '</div>'; // do_shortcode allows for nested Shortcodes
}

// Shortcode Demo with simple <h2> tag
function html5_shortcode_demo_2($atts, $content = null) // Demo Heading H2 shortcode, allows for nesting within above element. Fully expandable.
{
    return '<h2>' . $content . '</h2>';
}

// Codepen
function shortcode_codepen($atts, $content = null)
{
	extract(shortcode_atts(array(
    'hash' => false,
    'user' => 'nessthehero',
    'height' => '268',
    'theme' => '0',
    'tab' => 'result'
    ), $atts));

	if ($hash !== false) {

	return '<p data-height="'+esc_attr($height)+'" data-theme-id="'+esc_attr($theme)+'" data-slug-hash="'+esc_attr($hash)+'" data-user="'+esc_attr($user)+'" data-default-tab="'+esc_attr($tab)+'" class="codepen">See the Pen <a href="http://codepen.io/'+esc_attr($user)+'/pen/'+esc_attr($hash)+'">'+esc_html($hash)+'</a> by me (<a href="http://codepen.io/'+esc_attr($user)+'">@'+esc_html($user)+'</a>) on <a href="http://codepen.io">CodePen</a></p>
<script async src="http://codepen.io/assets/embed/ei.js"></script>';

	} else {
		return '';
	}

}

// Shortcodes
add_shortcode('html5_shortcode_demo', 'html5_shortcode_demo'); // You can place [html5_shortcode_demo] in Pages, Posts now.
add_shortcode('html5_shortcode_demo_2', 'html5_shortcode_demo_2'); // Place [html5_shortcode_demo_2] in Pages, Posts now.
add_shortcode('codepen', 'shortcode_codepen');
