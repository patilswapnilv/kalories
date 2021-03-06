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

 <?php acf_form_head(); ?>
 <?php get_header(); ?>

 	<div id="primary" class="content-area">
		<h1> Select your meals </h1>
 		<div id="content" class="site-content" role="main">

 			<?php /* The loop */ ?>
 			<?php while (have_posts()) : the_post(); ?>

 				<?php acf_form(array(
					'post_id'		=> 'new_post',
					'new_post'		=> array(
						'post_type'		=> 'kalories-cal',
						'post_status'		=> 'publish'
					),
					'submit_value'		=> 'Add new'
				)); ?>

 			<?php endwhile; ?>

 		</div><!-- #content -->
 	</div><!-- #primary -->

 <?php get_sidebar(); ?>
 <?php get_footer(); ?>
