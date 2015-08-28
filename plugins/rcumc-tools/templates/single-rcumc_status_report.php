<?php
/**
 * The template for displaying a single RCUMC Status Report
 */
global $post;

// Call the Template Header
get_header();
?>


<div id="primary">
<div id="content" role="main">
	<?php if ( have_posts() ) { ?>
<?php while ( have_posts() ) : 	the_post(); ?> 
<article id="status-report-<?php the_ID(); ?>" <?php post_class(); ?> > 
<?php  the_title('<h2>','</h2>'); ?>
<?php  
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


</article>
<?php endwhile; ?>

<?php } else { ?>
<h1> No Status Reports Found </h1>
		<?php } ?>

</div> <!-- id="content" -->
</div> <!-- id="primary" -->



<?php
// Call the Template Footer
get_footer();
?>
