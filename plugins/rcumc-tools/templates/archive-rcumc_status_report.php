<?php
/**
 * The template for displaying list of RCUMC Status Reports.
 */

// Call the template header
get_header();

?>

<div id="primary" class="site-content"?
	<div id="content" role="main">

	<header class="page-header">
	<h1 class="page-title">
	<?php echo __('RCUMC Status Reports', 'RCUMC_TOOLS_TD'); ?>
	</h1>
	</header>

	<?php if ( have_posts() ) { ?>
	<?php global $wp_query; ?>
    <?php if ( $wp_query->max_num_pages > 1 ) : ?>
	<!-- What do we want to display here -->
	<?php endif; /* $wp_query->max_num_pages */ ?>

	<?php /* Start the Loop */ ?>
	<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID() ?>" <?php post_class(); ?> >
        <header class="entry-header">
	<h2><?php the_title(); ?></h2>
    </article>
    <?php endwhile; /* the Loop While */ ?>

    <?php } else { /* have_posts() */ ?>
    <h2>No Status Reports found.</h2>

	<?php }  /* have_posts() */ ?>


	</div> <!-- #content -->
</div> <!-- #primary -->

<?php
// Call the template sidebar and footer.
	get_sidebar();
    get_footer();
?>