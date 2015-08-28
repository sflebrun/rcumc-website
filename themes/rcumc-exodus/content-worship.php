<?php
/**
 * Worship post content for:
 *
 * 1. Full / Single
 * 2. Short / Multiple
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;

/*************************************
 * 1. SINGLE
 *************************************/

if ( is_singular( get_post_type() ) ) : 

?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'exodus-entry-full exodus-blog-full' ); ?>>

		<?php get_template_part( 'content-worship-header' ); ?>

		<div class="exodus-entry-content exodus-clearfix">

			<?php the_content(); ?>

			<?php do_action( 'exodus_after_content' ); ?>

		</div>

		<?php get_template_part( 'content-footer-full' ); // multipage nav, term lists, "Edit" button, etc. ?>

	</article>

<?php

/*************************************
 * 2.  MULTIPLE
 *************************************/

else :

?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'exodus-entry-short exodus-blog-short' ); ?>>

		<?php get_template_part( 'content-worship-header' ); ?>

		<?php the_content(); ?>

		<?php get_template_part( 'content-footer-short' ); // show appropriate button(s) ?>

	</article>

<?php endif; ?> 
