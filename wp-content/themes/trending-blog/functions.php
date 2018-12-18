<?php
/*
 * Trending Blog functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Trending Blog
*/

// Loads parent theme stylesheet
function trending_blog_setup() {
        add_image_size('trending-blog-slider', 512, 640, true);
}
add_action('after_setup_theme', 'trending_blog_setup');
// Do not delete this
function trending_blog_scripts()
{
    wp_enqueue_style('galway-lite', get_template_directory_uri() . '/style.css');
}

add_action('wp_enqueue_scripts', 'trending_blog_scripts', 20);




// Loads custom stylesheet and js for child. 
// This could override all stylesheets of parent theme and custom js functions
function trending_blog_custom_scripts()
{

    $fonts_url = galway_lite_fonts_url();
    if (!empty($fonts_url)) {
        wp_enqueue_style('galway-lite-google-fonts', $fonts_url, array(), null);
    }

    wp_enqueue_style('trending-blog', get_stylesheet_directory_uri() . '/assets/custom.css');

    wp_enqueue_script('minimal-script', get_stylesheet_directory_uri().'/assets/custom.js', array('jquery'), '', true);
}

add_action('wp_enqueue_scripts', 'trending_blog_custom_scripts', 60);

require_once( get_stylesheet_directory(). '/inc/hooks/slider.php' );


//* Add description to menu items
add_filter( 'walker_nav_menu_start_el', 'trending_blog_add_description', 10, 2 );
function trending_blog_add_description( $item_output, $item ) {
    $description = $item->post_content;
    if (('' !== $description) && (' ' !== $description) ) {
        return preg_replace( '/(<a.*)</', '$1' . '<span class="menu-description">' . $description . '</span><', $item_output) ;
    }
    else {
        return $item_output;
    };
}


if (!function_exists('galway_lite_fonts_url')):

/**
 * Return fonts URL.
 *
 * @since 1.0.0
 * @return string Fonts URL.
 */
function galway_lite_fonts_url() {
	$fonts_url = '';
	$fonts     = array();

	$galway_lite_primary_font   = 'Playfair+Display:400,400i,700,700i,900,900i';
	$galway_lite_secondary_font = 'Source+Sans+Pro:400,400i,700,700i';

	$galway_lite_fonts   = array();
	$galway_lite_fonts[] = $galway_lite_primary_font;
	$galway_lite_fonts[] = $galway_lite_secondary_font;

	$galway_lite_fonts_stylesheet = '//fonts.googleapis.com/css?family=';

	$i = 0;
	for ($i = 0; $i < count($galway_lite_fonts); $i++) {

		if ('off' !== sprintf(_x('on', '%s font: on or off', 'galway-lite'), $galway_lite_fonts[$i])) {
			$fonts[] = $galway_lite_fonts[$i];
		}

	}

	if ($fonts) {
		$fonts_url = add_query_arg(array(
				'family' => urldecode(implode('|', $fonts)),
			), 'https://fonts.googleapis.com/css');
	}

	return $fonts_url;
}

endif;
