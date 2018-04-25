<?php
/**
 * Template part for displaying kalories-cal archive
 *
 * Template Name: Kalories Calculations
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Kalories
 * @subpackage Kalories/public/templates
 * @since 1.0
 * @version 1.0
 */

?>

<<<<<<< HEAD
<?php
 $query = new WP_Query( array('post_type' => 'kalories-cal', 'posts_per_page' => 5 ) );
 while ( $query->have_posts() ) : $query->the_post(); ?>
// Your code e.g. "the_content();"
<div class="entry-content">
 <?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail();
=======
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	if (is_sticky() && is_home()) :
		echo twentyseventeen_get_svg(array('icon' => 'thumb-tack'));
	endif;
	?>
	<header class="entry-header">
		<?php
		if ('post' === get_post_type()) {
			echo '<div class="entry-meta">';
			if (is_single()) {
				twentyseventeen_posted_on();
			} else {
				echo twentyseventeen_time_link();
				twentyseventeen_edit_link();
			};
			echo '</div><!-- .entry-meta -->';
		};

		if (is_single()) {
			the_title('<h1 class="entry-title">', '</h1>');
		} elseif (is_front_page() && is_home()) {
			the_title('<h3 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>');
		} else {
			the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
>>>>>>> dev
		}
			the_content();
		?>
<<<<<<< HEAD
</div>
<?php //endif;
wp_reset_postdata(); ?>
<?php endwhile; ?>
=======
	</header><!-- .entry-header -->

	<?php if ('' !== get_the_post_thumbnail() && !is_single()) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail('twentyseventeen-featured-image'); ?>
			</a>
		</div><!-- .post-thumbnail -->
	<?php endif; ?>

	<div class="entry-content">
		<?php
		/* translators: %s: Name of current post */
		the_content(
			sprintf(
				__('Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen'),
				get_the_title()
			)
		);

		wp_link_pages(
			array(
				'before'      => '<div class="page-links">' . __('Pages:', 'twentyseventeen'),
				'after'       => '</div>',
				'link_before' => '<span class="page-number">',
				'link_after'  => '</span>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php
	if (is_single()) {
		twentyseventeen_entry_footer();
	}
	?>

</article><!-- #post-## -->
>>>>>>> dev
