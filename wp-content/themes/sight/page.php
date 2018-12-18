<?php get_header(); ?>

	<?php if (have_posts()): ?>
		<?php while (have_posts()): the_post(); ?>

		<div class="entry">
			<div <?php post_class('single clear'); ?> id="post_<?php the_ID(); ?>">
				<div class="post-meta">
					<h1><?php the_title(); ?></h1>
					<?php printf(__('by <span class="post-author"><a href="%s" title="Posts by %s">%s</a></span> on <span class="post-date">%s</span> &bull; <span>%s</span>', 'sight'),
						get_author_posts_url(get_the_author_meta('ID')),
						get_the_author(),
						get_the_author(),
						get_the_time('M j, Y'),
						get_the_time()
					); ?>
					<?php edit_post_link( __( 'Edit entry', 'sight'), '&bull; '); ?>
					<?php if (comments_open()): ?>
						<a href="#comments" class="post-comms"><?php comments_number(__('Нет комментариев ', 'sight'), __('1 коментарий', 'sight'), __('% Comments', 'sight'), '', __('Комментарии закрыты', 'sight') ); ?></a>
					<?php endif; ?>
				</div>
				<div class="post-content"><?php the_content(); ?></div>
				<div class="post-footer"><?php the_tags('<strong>'.__('Tags:', 'sight').' </strong>', ', '); ?></div>
			</div>
		</div>

		<?php endwhile; ?>
	<?php endif; ?>

<?php comments_template(); ?>

<?php get_footer(); ?>