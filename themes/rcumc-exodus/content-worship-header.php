<?php
/**
 * Post Worship Header Meta (Full and Short)
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;

?>
<header class="exodus-entry-header exodus-clearfix">

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="exodus-entry-image">
			<?php exodus_post_image(); ?>
		</div>
	<?php endif; ?>

	<div class="exodus-entry-title-meta">
 		<?php if ( ctfw_has_title() ) : ?>
                    <h1 class="exodus-entry-title<?php if ( is_singular( get_post_type() ) ) : ?> exodus-main-title<?php endif; ?>">
			<?php exodus_post_title(); 
                        $format = "n/j/y  g:i a";
                        $datetime = get_field('service_date');
                        echo '<br/>' . date_i18n( $format, $datetime ); ?>                                     
                        <br/> 
                        </h1>
                <?php endif; ?>
		

		<ul class="exodus-entry-meta">
                      <?php if ( $liturgical = get_the_term_list( $post->ID, 'liturgical', '', __( ', ', 'exodus' ) ) ) : ?>
				<ul class="exodus-entry-byline">
					<?php echo $liturgical; ?>
                                </ul>
			<?php endif; ?>	
                      <?php if ( $musicgroups = get_the_term_list( $post->ID, 'music_group', 'Music:  ', __( ', ', 'exodus' ) ) ) : ?>
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

	</div>

</header>
