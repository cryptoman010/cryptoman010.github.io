<?php if (comments_open()): ?>
<div class="comments">
	<?php if (post_password_required()): ?>
	<p class="nopassword"><?php _e('This post is password protected. Enter the password to view any comments.', 'sight'); ?></p>
</div><!-- #comments -->
	<?php
			/* Stop the rest of comments.php from being processed,
			 * but don't kill the script entirely -- we still have
			 * to fully load the template.
			 */
			return;
		endif;
	?>

	<?php
		// You can start editing here -- including this comment!
	?>

	<div id="comments">
	<?php if (have_comments()): ?>
		<h3><?php printf(_n('1 комментарий', '%1$s comments', get_comments_number()), number_format_i18n( get_comments_number() ), '' ); ?></h3>
		<div class="comment_list">

			<!-- <div class="navigation">
				<div class="alignleft"><?php previous_comments_link() ?></div>
				<div class="alignright"><?php next_comments_link() ?></div>
			</div> -->

			<ol>
				<?php wp_list_comments(array('callback' => 'commentslist')); ?>
			</ol>

			<!-- <div class="navigation">
				<div class="alignleft"><?php previous_comments_link() ?></div>
				<div class="alignright"><?php next_comments_link() ?></div>
			</div> -->

		</div>
	<?php endif; // end have_comments() ?>
	</div>

	<?php if ('open' == $post->comment_status): ?>

		<?php comment_form(array(
			'must_log_in' => '<p class="comment_message">'
				.sprintf('You must be <a href="%s">logged in</a> to post a comment.',
					get_option('siteurl').'/wp-login.php?redirect_to='.urlencode(get_permalink()))
				.'</p>',
			'logged_in_as' => '<p class="comment_message">'
				.sprintf('Logged in as <a href="%s">%s</a>. <a href="%s" title="Выйти из этого аккаунта">Log out &raquo;</a>',
					get_option('siteurl').'/wp-admin/profile.php',
					$user_identity,
					wp_logout_url(get_permalink()))
				.'</p>',
			'comment_notes_before' => '',
			'comment_notes_after' => '',
			'title_reply' => __('А что вы думаете по этому поводу?', 'sight'),
			'label_submit' => __('Подтвердить', 'sight'),
			'comment_field' => '<div class="commform-textarea"><div><textarea name="comment" id="comment" cols="50" rows="7" tabindex="1"></textarea></div></div>',
			'fields' => apply_filters('comment_form_default_fields', array(
				'author' => '<div class="commform-author"><p>'.__('Name', 'sight').' <span>'.__('required', 'sight').'</span></p><div><input type="text" name="author" id="author" tabindex="2" /></div></div>',
				'email' => '<div class="commform-email"><p>'.__('Email', 'sight').' <span>'.__('required', 'sight').'</span></p><div><input type="text" name="email" id="email" tabindex="3" /></div></div>',
				'url' => '<div class="commform-url"><p>'.__('Website', 'sight').'</p><div><input type="text" name="url" id="url" tabindex="4" /></div></div>'
			))
		)); ?>

	<?php endif; ?>

</div>
<?php endif; // end ! comments_open() ?>
<!-- #comments -->
