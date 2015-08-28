<?php
/**
 * The template for displaying a single event
 *
 * Please note that since 1.7, this template is not used by default. You can edit the 'event details'
 * by using the event-meta-event-single.php template.
 *
 * Or you can edit the entire single event template by creating a single-event.php template
 * in your theme. You can use this template as a guide.
 *
 * For a list of available functions (outputting dates, venue details etc) see http://codex.wp-event-organiser.com/
 *
 ***************** NOTICE: *****************
 *  Do not make changes to this file. Any changes made to this file
 * will be overwritten if the plug-in is updated.
 *
 * To overwrite this template with your own, make a copy of it (with the same name)
 * in your theme directory. See http://docs.wp-event-organiser.com/theme-integration for more information
 *
 * WordPress will automatically prioritise the template in your theme directory.
 ***************** NOTICE: *****************
 *
 * @package Event Organiser (plug-in)
 * @since 1.0.0
 */

//Call the template header
get_header(); ?>

<div id="primary">
	<div id="content" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<header class="entry-header">
                        <h1 class="entry-title">
                        <?php 
                        //To Display thumbnails
                        if ( has_post_thumbnail() ) { // check if the event has a thumbnail assigned to it.
                        the_post_thumbnail(array(100,100));
                        } ?>
				<!-- Display event title -->
				<?php the_title(); 
                                $date= eo_get_the_start('n/j/y  g:i a');
                                echo '</h1> <br/> <h2>' . $date;  ?></h2>

			</header><!-- .entry-header -->
	                <ul>
			<div class="entry-content">
                                <!-- The content or the description of the event-->
				<ul class="exodus-entry-meta">
                      <?php if ( $liturgical = get_the_term_list( $post->ID, 'liturgical_info', '', __( ', ', 'exodus' ) ) ) : ?>
				<ul class="exodus-entry-byline">
					<?php echo $liturgical; ?>
                                </ul>
			<?php endif; ?>	
                      <?php if ( $musicgroups = get_the_term_list( $post->ID, 'musicians', 'Music:  ', __( ', ', 'exodus' ) ) ) : ?>
				<ul class="exodus-entry-byline">
					<?php echo $musicgroups ; ?>
				</ul>
			<?php endif; ?>
 
          
       
	              <?php if ( exodus_show_comments() ) : ?>
				<ul class="exodus-entry-comments-link exodus-content-icon">
					<span class="<?php exodus_icon_class( 'comments-link' ); ?>"></span>
					<?php exodus_comments_link(); ?>
				</ul>
			<?php endif; ?>

		</ul>

                                <?php the_content(); ?>
                                 
				<!-- Get event information, see template: event-meta-event-single.php -->
				<?php eo_get_template_part('event-meta','event-single'); ?>

			</div><!-- .entry-content -->

			<footer class="entry-meta">
			<?php
				//Events have their own 'event-category' taxonomy. Get list of categories this event is in.
				$categories_list = get_the_term_list( get_the_ID(), 'event-category', '', ', ',''); 

				if ( '' != $categories_list ) {
					$utility_text = __( 'This event was posted in %1$s by <a href="%5$s">%4$s</a>. Bookmark the <a href="%2$s" title="Permalink to %3$s" rel="bookmark">permalink</a>.', 'eventorganiser' );
				} else {
					$utility_text = __( 'This event was posted by <a href="%5$s">%4$s</a>. Bookmark the <a href="%2$s" title="Permalink to %3$s" rel="bookmark">permalink</a>.', 'eventorganiser' );
				}
				printf($utility_text,
					$categories_list,
					esc_url( get_permalink() ),
					the_title_attribute( 'echo=0' ),
					get_the_author(),
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) )
				);
			?>

			<?php edit_post_link( __( 'Edit'), '<span class="edit-link">', '</span>' ); ?>
			</footer><!-- .entry-meta -->

			</article><!-- #post-<?php the_ID(); ?> -->

			<!-- If comments are enabled, show them -->
			<div class="comments-template">
				<?php comments_template(); ?>
			</div>				

		<?php endwhile; // end of the loop. ?>

	</div><!-- #content -->
</div><!-- #primary -->

<!-- Call template footer -->
<?php get_footer(); ?>
