<?php
class GetConnected extends WP_Widget {
	function __construct() {
		parent::__construct(
			'getconnected',
			__('Sight Social Links', 'sight')
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);

		echo $before_widget;

		if ($title) echo $before_title.$title.$after_title;
		else echo '<div class="widget-body clear">';
		?>

		<!-- Twitter -->
		<?php if (get_option('twitter_url')): ?>
		<div class="getconnected_twitter">
			<a href="<?php echo get_option('twitter_url'); ?>">Twitter</a>
			<span><?php echo twittercount(get_option('twitter_url')); ?> <?php _e('followers', 'sight'); ?></span>
		</div>
		<?php endif; ?>
		<!-- /Twitter -->

		<!-- Facebook -->
		<?php if (get_option('fb_url')): ?>
		<div class="getconnected_fb">
			<a href="<?php echo get_option('fb_url'); ?>">Facebook</a>
			<span><?php echo get_option('fb_text'); ?></span>
		</div>
		<?php endif; ?>
		<!-- /Facebook -->

		<!-- Flickr -->
		<?php if (get_option('flickr_url')): ?>
		<div class="getconnected_flickr">
			<a href="<?php echo get_option('flickr_url'); ?>"><?php _e('Flickr group', 'sight'); ?></a>
			<span><?php echo get_option('flickr_text'); ?></span>
		</div>
		<?php endif; ?>
		<!-- /Flickr -->

		<!-- Behance -->
		<?php if (get_option('behance_url')): ?>
		<div class="getconnected_behance">
			<a href="<?php echo get_option('behance_url'); ?>">Behance</a>
			<span><?php echo get_option('behance_text'); ?></span>
		</div>
		<?php endif; ?>
		<!-- /Behance -->

		<!-- Delicious -->
		<?php if (get_option('delicious_url')): ?>
		<div class="getconnected_delicious">
			<a href="<?php echo get_option('delicious_url'); ?>">Delicious</a>
			<span><?php echo get_option('delicious_text'); ?></span>
		</div>
		<?php endif; ?>
		<!-- /Delicious -->

		<!-- Stumbleupon -->
		<?php if (get_option('stumbleupon_url')): ?>
		<div class="getconnected_stumbleupon">
			<a href="<?php echo get_option('stumbleupon_url'); ?>">Stumbleupon</a>
			<span><?php echo get_option('stumbleupon_text'); ?></span>
		</div>
		<?php endif; ?>
		<!-- /Stumbleupon -->

		<!-- Tumblr -->
		<?php if (get_option('tumblr_url')): ?>
		<div class="getconnected_tumblr">
			<a href="<?php echo get_option('tumblr_url'); ?>">Tumblr</a>
			<span><?php echo get_option('tumblr_text'); ?></span>
		</div>
		<?php endif; ?>
		<!-- /Tumblr -->

		<!-- Vimeo -->
		<?php if (get_option('vimeo_url')): ?>
		<div class="getconnected_vimeo">
			<a href="<?php echo get_option('vimeo_url'); ?>">Vimeo</a>
			<span><?php echo get_option('vimeo_text'); ?></span>
		</div>
		<?php endif; ?>
		<!-- /Vimeo -->

		<!-- Youtube -->
		<?php if (get_option('youtube_url')): ?>
		<div class="getconnected_youtube">
			<a href="<?php echo get_option('youtube_url'); ?>">Youtube</a>
			<span><?php echo get_option('youtube_text'); ?></span>
		</div>
		<?php endif; ?>
		<!-- /Youtube -->

		<?php
		echo $after_widget;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance) {
		if (isset($instance['title'])) $title = esc_attr($instance['title']);
		else $title = __('Sight Social Links', 'sight');
		?>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'sight'); ?>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>
		</p>

		<script type="text/javascript">
		(function($) {
			$(function() {
				$('.social_options').hide();
				$('.social_title').toggle(
					function() {
						$(this).next().slideDown(100)
					},
					function() {
						$(this).next().slideUp(100)
					}
				);
			})
		})(jQuery)
		</script>

		<div style="margin-bottom: 5px;">
			<a href="javascript: void(0);" class="social_title" style="font-size: 13px; display: block; margin-bottom: 5px;">Twitter</a>
			<p class="social_options">
				<label for="twitter_url"><?php _e('Profile url:', 'sight'); ?></label>
				<input type="text" name="twitter_url" id="twitter_url" class="widefat" value="<?php echo get_option('twitter_url'); ?>"/>
			</p>
		</div>

		<div style="margin-bottom: 5px;">
			<a href="javascript: void(0);" class="social_title" style="font-size: 13px; display: block; margin-bottom: 5px;">Facebook</a>
			<p class="social_options">
				<label for="fb_url"><?php _e('Profile url:', 'sight'); ?></label>
				<input type="text" name="fb_url" id="fb_url" class="widefat" value="<?php echo get_option('fb_url'); ?>"/>
				<label for="fb_text"><?php _e('Description:', 'sight'); ?></label>
				<input type="text" name="fb_text" id="fb_text" class="widefat" value="<?php echo get_option('fb_text'); ?>"/>
			</p>
		</div>

		<div style="margin-bottom: 5px;">
			<a href="javascript: void(0);" class="social_title" style="font-size: 13px; display: block; margin-bottom: 5px;">Flickr</a>
			<p class="social_options">
				<label for="flickr_url"><?php _e('Profile url:', 'sight'); ?></label>
				<input type="text" name="flickr_url" id="flickr_url" class="widefat" value="<?php echo get_option('flickr_url'); ?>"/>
				<label for="flickr_text"><?php _e('Description:', 'sight'); ?></label>
				<input type="text" name="flickr_text" id="flickr_text" class="widefat" value="<?php echo get_option('flickr_text'); ?>"/>
			</p>
		</div>

		<div style="margin-bottom: 5px;">
			<a href="javascript: void(0);" class="social_title" style="font-size: 13px; display: block; margin-bottom: 5px;">Behance</a>
			<p class="social_options">
				<label for="behance_url"><?php _e('Profile url:', 'sight'); ?></label>
				<input type="text" name="behance_url" id="behance_url" class="widefat" value="<?php echo get_option('behance_url'); ?>"/>
				<label for="behance_text"><?php _e('Description:', 'sight'); ?></label>
				<input type="text" name="behance_text" id="behance_text" class="widefat" value="<?php echo get_option('behance_text'); ?>"/>
			</p>
		</div>

		<div style="margin-bottom: 5px;">
			<a href="javascript: void(0);" class="social_title" style="font-size: 13px; display: block; margin-bottom: 5px;">Delicious</a>
			<p class="social_options">
				<label for="delicious_url"><?php _e('Profile url:', 'sight'); ?></label>
				<input type="text" name="delicious_url" id="delicious_url" class="widefat" value="<?php echo get_option('delicious_url'); ?>"/>
				<label for="delicious_text"><?php _e('Description:', 'sight'); ?></label>
				<input type="text" name="delicious_text" id="delicious_text" class="widefat" value="<?php echo get_option('delicious_text'); ?>"/>
			</p>
		</div>

		<div style="margin-bottom: 5px;">
			<a href="javascript: void(0);" class="social_title" style="font-size: 13px; display: block; margin-bottom: 5px;">Stumbleupon</a>
			<p class="social_options">
				<label for="stumbleupon_url"><?php _e('Profile url:', 'sight'); ?></label>
				<input type="text" name="stumbleupon_url" id="stumbleupon_url" class="widefat" value="<?php echo get_option('stumbleupon_url'); ?>"/>
				<label for="stumbleupon_text"><?php _e('Description:', 'sight'); ?></label>
				<input type="text" name="stumbleupon_text" id="stumbleupon_text" class="widefat" value="<?php echo get_option('stumbleupon_text'); ?>"/>
			</p>
		</div>

		<div style="margin-bottom: 5px;">
			<a href="javascript: void(0);" class="social_title" style="font-size: 13px; display: block; margin-bottom: 5px;">Tumblr</a>
			<p class="social_options">
				<label for="tumblr_url"><?php _e('Profile url:', 'sight'); ?></label>
				<input type="text" name="tumblr_url" id="tumblr_url" class="widefat" value="<?php echo get_option('tumblr_url'); ?>"/>
				<label for="tumblr_text"><?php _e('Description:', 'sight'); ?></label>
				<input type="text" name="tumblr_text" id="tumblr_text" class="widefat" value="<?php echo get_option('tumblr_text'); ?>"/>
			</p>
		</div>

		<div style="margin-bottom: 5px;">
			<a href="javascript: void(0);" class="social_title" style="font-size: 13px; display: block; margin-bottom: 5px;">Vimeo</a>
			<p class="social_options">
				<label for="vimeo_url"><?php _e('Profile url:', 'sight'); ?></label>
				<input type="text" name="vimeo_url" id="vimeo_url" class="widefat" value="<?php echo get_option('vimeo_url'); ?>"/>
				<label for="vimeo_text"><?php _e('Description:', 'sight'); ?></label>
				<input type="text" name="vimeo_text" id="vimeo_text" class="widefat" value="<?php echo get_option('vimeo_text'); ?>"/>
			</p>
		</div>

		<div style="margin-bottom: 5px;">
			<a href="javascript: void(0);" class="social_title" style="font-size: 13px; display: block; margin-bottom: 5px;">Youtube</a>
			<p class="social_options">
				<label for="youtube_url"><?php _e('Profile url:', 'sight'); ?></label>
				<input type="text" name="youtube_url" id="youtube_url" class="widefat" value="<?php echo get_option('youtube_url'); ?>"/>
				<label for="youtube_text"><?php _e('Description:', 'sight'); ?></label>
				<input type="text" name="youtube_text" id="youtube_text" class="widefat" value="<?php echo get_option('youtube_text'); ?>"/>
			</p>
		</div>

		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);

		update_option('twitter_url', $_POST['twitter_url']);
		update_option('fb_url', $_POST['fb_url']);
		update_option('flickr_url', $_POST['flickr_url']);
		update_option('behance_url', $_POST['behance_url']);
		update_option('delicious_url', $_POST['delicious_url']);
		update_option('stumbleupon_url', $_POST['stumbleupon_url']);
		update_option('tumblr_url', $_POST['tumblr_url']);
		update_option('vimeo_url', $_POST['vimeo_url']);
		update_option('youtube_url', $_POST['youtube_url']);
		update_option('fb_text', $_POST['fb_text']);
		update_option('flickr_text', $_POST['flickr_text']);
		update_option('behance_text', $_POST['behance_text']);
		update_option('delicious_text', $_POST['delicious_text']);
		update_option('stumbleupon_text', $_POST['stumbleupon_text']);
		update_option('tumblr_text', $_POST['tumblr_text']);
		update_option('vimeo_text', $_POST['vimeo_text']);
		update_option('youtube_text', $_POST['youtube_text']);

		return $instance;
	}
}
