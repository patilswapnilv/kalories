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

<?php
 $query = new WP_Query( array('post_type' => 'kalories-cal', 'posts_per_page' => 5 ) );
 while ( $query->have_posts() ) : $query->the_post(); ?>
// Your code e.g. "the_content();"
<div class="entry-content">
 <?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail();
		}
			the_content();
		?>
</div>
<?php endif; wp_reset_postdata(); ?>
<?php endwhile; ?>
