<?php
/**
 * Sight functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link http://codex.wordpress.org/Theme_Development
 * @link http://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * @link http://codex.wordpress.org/Plugin_API
 */

/**
 * Set up the content width value based on the theme's design.
 */
if (!isset($content_width)) {
	$content_width = 610;
}

/**
 * Sight setup.
 *
 * Set up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support post thumbnails.
 */
function sight_setup() {
	/*
	 * Make Sight available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Sight, use a find and
	 * replace to change 'sight' to the name of your theme in all
	 * template files.
	 */
	load_theme_textdomain('sight', get_template_directory().'/languages');

	// Add RSS feed links to <head> for posts and comments.
	add_theme_support('automatic-feed-links');

	// Enable support for Post Thumbnails
	add_theme_support('post-thumbnails');
	add_image_size('mini-thumbnail', 50, 50, true);
	add_image_size('slide', 640, 290, true);

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus(array(
		'navigation' => __('Navigation', 'sight'),
		'top_menu' => __('Top menu', 'sight'),
	));

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support('html5', array(
		'search-form', 'comment-form', 'comment-list',
	));
}
add_action('after_setup_theme', 'sight_setup');

/**
 * Changes default image size when theme is activated
 */
function sight_thumbnail_size() {
	update_option('thumbnail_size_w', 290);
	update_option('thumbnail_size_h', 290);
}
add_action('after_switch_theme', 'sight_thumbnail_size');

/**
 * Register Sight widget areas.
 *
 * @return void
 */
function sight_widgets_init() {
	require get_template_directory().'/widgets/get-connected.php';
	require get_template_directory().'/widgets/recent-posts.php';
	register_widget('GetConnected');
	register_widget('Recentposts_thumbnail');

	register_sidebar(array(
		'name' => __('Site description', 'sight'),
		'before_widget' => '<div class="site-description">',
		'after_widget' => '</div>'
	));
	register_sidebar(array(
		'name' => __('Sidebar', 'sight'),
		'before_widget' => '<div id="%1$s" class="%2$s widget">',
		'after_widget' => '</div></div>',
		'before_title' => '<h3>',
		'after_title' => '</h3><div class="widget-body clear">'
	));
}
add_action('widgets_init', 'sight_widgets_init');

/**
 * Enqueue scripts and styles for the front end.
 *
 * @return void
 */
function sight_scripts() {
	// Load our main stylesheet.
	wp_enqueue_style('sight-style', get_stylesheet_uri());

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style('sight-ie', get_template_directory_uri().'/ie.css', array('sight-style'), '20131217');
	wp_style_add_data('sight-ie', 'conditional', 'IE');

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}

	wp_enqueue_script('cycle', get_template_directory_uri().'/js/jquery.cycle.all.min.js', array('jquery'), '20131219', false);
	wp_enqueue_script('cookie', get_template_directory_uri().'/js/jquery.cookie.js', array('jquery'), '20131219', false);
	wp_enqueue_script('script', get_template_directory_uri().'/js/script.js', array('jquery'), '20131219', true);
}
add_action('wp_enqueue_scripts', 'sight_scripts');

if (is_admin() && isset($_GET['activated']) && $pagenow == 'themes.php') {
	update_option('posts_per_page', 12);
	update_option('paging_mode', 'default');
}

class extended_walker extends Walker_Nav_Menu {
	function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
		if (!$element)
			return;

		$id_field = $this->db_fields['id'];

		// display this element
		if (is_array($args[0]))
			$args[0]['has_children'] = !empty($children_elements[$element->$id_field]);

		// Adds the 'parent' class to the current item if it has children
		if (!empty($children_elements[$element->$id_field]))
			array_push($element->classes, 'parent');

		$cb_args = array_merge(array(&$output, $element, $depth), $args);

		call_user_func_array(array(&$this, 'start_el'), $cb_args);

		$id = $element->$id_field;

		// descend only when the depth is right and there are childrens for this element
		if (($max_depth == 0 || $max_depth > $depth + 1) && isset($children_elements[$id])) {
			foreach ($children_elements[$id] as $child) {
				if (!isset($newlevel)) {
					$newlevel = true;
					//start the child delimiter
					$cb_args = array_merge(array(&$output, $depth), $args);
					call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
				}
				$this->display_element($child, $children_elements, $max_depth, $depth + 1, $args, $output);
			}
			unset($children_elements[$id]);
		}

		if (isset($newlevel) && $newlevel) {
			// end the child delimiter
			$cb_args = array_merge(array(&$output, $depth), $args);
			call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
		}

		// end this element
		$cb_args = array_merge(array(&$output, $element, $depth), $args);
		call_user_func_array(array(&$this, 'end_el'), $cb_args);
	}
}

/**
 * Slideshow
 */
// Callback function to show fields in meta box
function sight_show_box() {
	global $post;
	$field = array('name' => 'Show in slideshow', 'id' => 'sgt_slide', 'type' => 'checkbox');
	// get current post meta data
	$meta = get_post_meta($post->ID, $field['id'], true);

	// Use nonce for verification
	?>
	<input type="hidden" name="sight_meta_box_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>" />

	<table class="form-table">
		<tr>
			<th style="width:50%"><label for="<?php echo $field['id']; ?>"><?php echo $field['name']; ?></label></th>
			<td>
				<input type="checkbox" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>"<?php echo $meta ? ' checked="checked"' : ''; ?> />
			<td>
		</tr>
	</table>

	<?php
}

// Add meta box
function sight_add_box() {
	add_meta_box('slide', 'Slideshow Options', 'sight_show_box', 'post', 'side', 'low');
}
add_action('add_meta_boxes', 'sight_add_box');

// Save data from meta box
function sight_save_data($post_id) {
	// verify nonce
	if (!wp_verify_nonce($_POST['sight_meta_box_nonce'], basename(__FILE__))) {
		return $post_id;
	}

	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	}
	elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}

	$old = get_post_meta($post_id, 'sgt_slide', true);
	$new = $_POST['sgt_slide'];

	if ($new && $new != $old) {
		update_post_meta($post_id, 'sgt_slide', $new);
	}
	elseif ('' == $new && $old) {
		delete_post_meta($post_id, 'sgt_slide', $old);
	}
}
add_action('save_post', 'sight_save_data');

/**
 * Options
 */
function options_admin_menu() {
	// here's where we add our theme options page link to the dashboard sidebar
	add_theme_page("Sight Theme Options", "Theme Options", 'edit_themes', basename(__FILE__), 'options_page');
}
add_action('admin_menu', 'options_admin_menu');

function options_page() {
	// check options update
	if ($_POST['update_options'] == 'true') {
		update_option('logo_url', $_POST['logo_url']);
		update_option('bg_color', $_POST['bg_color']);
		update_option('ss_disable', $_POST['ss_disable']);
		update_option('ss_timeout', $_POST['ss_timeout']);
		update_option('paging_mode', $_POST['paging_mode']);
		update_option('ga', stripslashes_deep($_POST['ga']));
	}
	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br /></div>
		<h2><?php _e('Sight Theme Options', 'sight'); ?></h2>

		<form method="post" action="">
			<input type="hidden" name="update_options" value="true" />

			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="logo_url"><?php _e('Custom logo URL:', 'sight'); ?></label></th>
					<td><input type="text" name="logo_url" id="logo_url" size="50" value="<?php echo get_option('logo_url'); ?>"/><br/>
						<span class="description">
							<a href="<?php echo home_url(); ?>/wp-admin/media-new.php" target="_blank"><?php _e('Upload your logo</a> (max 290px x 128px) using WordPress Media Library and insert its URL here', 'sight'); ?>
						</span><br/><br/>
						<img src="<?php echo (get_option('logo_url')) ? get_option('logo_url') : get_template_directory_uri() . '/images/logo.png' ?>" alt=""/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="bg_color"><?php _e('Custom background color:', 'sight'); ?></label></th>
					<td><input type="text" name="bg_color" id="bg_color" size="20" value="<?php echo get_option('bg_color'); ?>"/><span
							class="description"> <?php _e('e.g., <strong>#27292a</strong> or <strong>black</strong>', 'sight'); ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="ss_disable"><?php _e('Disable slideshow:', 'sight'); ?></label></th>
					<td><input type="checkbox" name="ss_disable" id="ss_disable" <?php echo (get_option('ss_disable'))? 'checked="checked"' : ''; ?>/></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="ss_timeout"><?php _e('Timeout for slideshow (ms):', 'sight'); ?></label></th>
					<td><input type="text" name="ss_timeout" id="ss_timeout" size="20" value="<?php echo get_option('ss_timeout'); ?>"/><span
							class="description"> <?php _e('e.g., <strong>7000</strong>', 'sight'); ?></span></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label><?php _e('Pagination:', 'sight'); ?></label></th>
					<td>
						<input type="radio" name="paging_mode" value="default" <?php echo (get_option('paging_mode') == 'default') ? 'checked="checked"' : ''; ?>/><span class="description"><?php _e('Default + WP Page-Navi support', 'sight'); ?></span><br/>
						<input type="radio" name="paging_mode" value="ajax" <?php echo (get_option('paging_mode') == 'ajax') ? 'checked="checked"' : ''; ?>/><span class="description"><?php _e('AJAX-fetching posts', 'sight'); ?></span><br/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="ga"><?php _e('Google Analytics code:', 'sight'); ?></label></th>
					<td><textarea name="ga" id="ga" cols="48" rows="18"><?php echo get_option('ga'); ?></textarea></td>
				</tr>
			</table>

			<p><input type="submit" value="Save Changes" class="button button-primary" /></p>
		</form>
	</div>
	<?php
}

/**
 * Comments
 */
function commentslist($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li>
		<div id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<table>
				<tr>
					<td>
						<?php echo get_avatar($comment, 70, get_template_directory_uri().'/images/no-avatar.png'); ?>
					</td>
					<td>
						<div class="comment-meta">
							<?php printf(__('<p class="comment-author"><span>%s</span> says:</p>'), get_comment_author_link()) ?>
							<?php printf(__('<p class="comment-date">%s</p>'), get_comment_date('M j, Y')) ?>
							<?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
						</div>
					</td>
					<td>
						<div class="comment-text">
							<?php if ($comment->comment_approved == '0'): ?>
								<p><?php _e('Ваш комментарий ожидает модерации', 'sight') ?></p>
								<br/>
							<?php endif; ?>
							<?php comment_text() ?>
						</div>
					</td>
				</tr>
			</table>
		 </div>
<?php
}

function twittercount($twitter_url = 'http://twitter.com/wpshower') {
	$username = explode('/', $twitter_url);
	$username = end($username);

	$remote = wp_remote_get('https://twitter.com/'.$username);
	if ($remote instanceof WP_Error || $remote['response']['code'] != 200) return '0';

	$preg = preg_match(
		'/data-element-term="follower_stats" data-nav="followers"(.*?)>(.*?)<strong>(.*?)<\/strong>(.*?)Followers/s',
		$remote['body'],
		$matches
	);
	if (!$preg) return '0';

	return $matches[3];
}

function seo_title() {
	global $page, $paged;
	$sep = " | "; // delimiter
	$newtitle = get_bloginfo('name'); // default title

	// Single & Page
	if (is_single() || is_page())
		$newtitle = single_post_title("", false);

	// Category
	if (is_category())
		$newtitle = single_cat_title("", false);

	// Tag
	if (is_tag())
		$newtitle = single_tag_title("", false);

	// Search result
	if (is_search())
		$newtitle = "Search Result ".get_search_query();

	// Taxonomy
	if (is_tax()) {
		$curr_tax = get_taxonomy(get_query_var('taxonomy'));
		$curr_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy')); // current term data
		// if it's term
		if (!empty($curr_term)) {
			$newtitle = $curr_tax->label.$sep.$curr_term->name;
		}
		else {
			$newtitle = $curr_tax->label;
		}
	}

	// Page number
	if ($paged >= 2 || $page >= 2)
		$newtitle .= $sep.sprintf('Page %s', max($paged, $page));

	// Home & Front Page
	if (is_home() || is_front_page()) {
		$newtitle = get_bloginfo('name').$sep.get_bloginfo('description');
	}
	else {
		$newtitle .= $sep.get_bloginfo('name');
	}
	return $newtitle;
}
add_filter('wp_title', 'seo_title');

function new_excerpt_length($length) {
	return 200;
}
add_filter('excerpt_length', 'new_excerpt_length');

function getTinyUrl($url) {
	$remote = wp_remote_get('http://tinyurl.com/api-create.php?url='.$url);
	if ($remote instanceof WP_Error || $remote['response']['code'] != 200) {
		return 'tinyurl-error';
	}
	return $remote['body'];
}

function smart_excerpt($string, $limit) {
	$words = explode(" ", $string);
	if (count($words) >= $limit) $dots = '...';
	else $dots = '';
	echo implode(" ", array_splice($words, 0, $limit)).$dots;
}

function comments_link_attributes() {
	return 'class="comments_popup_link"';
}
add_filter('comments_popup_link_attributes', 'comments_link_attributes');

function next_posts_attributes() {
	return 'class="nextpostslink"';
}
add_filter('next_posts_link_attributes', 'next_posts_attributes');

function prev_posts_attributes() {
	return 'class="previouspostslink"';
}
add_filter('previous_posts_link_attributes', 'prev_posts_attributes');

$exl_posts = array();
