<?php
/**
 * Page Content for RCUMC Status Report (Full and Short)
**/

// No direct access
?>
           <article id="status-report-<?php the_ID(); ?>" <?php post_class(); ?> > 
           
               <h2 class="exodus-entry-title"><a href="<?php the_permalink() ?>"
                   title="the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
               <?php  
               if ( $GLOBALS[RCUMC_Status_Report::FULL_FLAG] )
               {
             $post_id = $post->ID; 
             $done_text = get_post_meta($post_id,
						   RCUMC_Status_Report_Fields::DONE_TEXT,
						   true);

             $todo_text = get_post_meta($post_id,
						   RCUMC_Status_Report_Fields::TODO_TEXT,
						   true);

             $help_text = get_post_meta($post_id,
						   RCUMC_Status_Report_Fields::HELP_TEXT,
						   true);

             $person_id = get_post_meta($post_id,
						   RCUMC_Status_Report_Fields::PERSON_ID,
						   true);

             $person = new WP_User($person_id);

             $person_name = $person->first_name . ' ' . $person->last_name;

             ?>
         <?php if ( '' != $done_text ) : ?>
        <strong>Did Last Week:&nbsp;</strong><br/>
        <?php echo $done_text; ?><br/>
        <?php endif; ?>

        <?php if ( '' != $todo_text ) : ?>
        <strong>Todo Next Week:&nbsp;</strong><br/>
        <?php echo $todo_text; ?><br/>
        <?php endif; ?>

        <?php if ( '' != $help_text ) : ?>
        <strong>Collabration Requests:&nbsp;</strong><br/>
        <?php echo $help_text; ?><br/>
        <?php endif; ?>
        <?php }  /* end of full flag */ ?>

        </article>
 