<?php
/**
 * Template Name: RCUMC Status Reports
 *
 * This shows a page with custom loop after the content
 *
 */


// Make query available via filter
add_filter('exodus_loop_after_content_query',
		   'rcumc_status_report_loop_after_content',
		   10, 0);

locate_template('index.php', true);

// Query listings according to selected options

function rcumc_status_report_loop_after_content()
{
	if ( isset($_REQUEST['due-date'] ) )
	{
		$query = rcumc_status_report_by_week_content(
			$_REQUEST['due-date']
			);
	}
	else if ( isset($_REQUEST['person-id']) )
	{
		$query = rcumc_status_report_by_name_content(
			$_REQUEST['person-id']
			);
	}
	else if ( isset($_REQUEST['start-date']) && isset($_REQUEST['stop-date']) )
	{
		$query = rcumc_status_report_by_date_range(
			$_REQUEST['start-date'], $_REQUEST['stop-date'] );
	}
	else
	{
		$query = rcumc_status_report_default_content();
	}
	return  $query;

}   // end of rcumc_status_report_loop_after_content()


function rcumc_status_report_default_content()
{
	$args = array(
		'post_type'       => RCUMC_Status_Report::TYPE_NAME,
		'posts_per_page'  => 8,
		'paged'           => ctfw_page_num(),
		'post_status'     => 'publish',
		'order'           => 'DESC',
		'orderby'         => 'meta_value_num',
		'meta_key'        => RCUMC_Status_Report_Fields::DUE_DATE,
		);

	return rcumc_status_report_query_orderby_last_and_first_name( $args );

}   // end of rcumc_status_report_default_content()

function rcumc_status_report_by_week_content( $due_date_text )
{
	$GLOBALS['rcumc-status-report-by-week-date'] = $due_date_text;

	// Determine the range of that Due Date falls in
	// Range Sunday to Saturday of date selected.
	$date_selected = strtotime( $due_date_text );

	$week = getdate( $date_selected );

	$start_day = $week['mday'] - $week['wday'];
	$end_day   = $start_day + 6;

	$start_date = mktime( 0, 0, 0, $week['mon'], $start_day, $week['year']);
	$end_date   = mktime(23,59,59, $week['mon'], $end_day,   $week['year']);

	$args = array(
		'post_type'       => RCUMC_Status_Report::TYPE_NAME,
		'posts_per_page'  => 8,
		'post_status'     => 'publish',
		'order'           => 'DESC',
		'orderby'         => 'meta_value_num',
		'meta_key'        => RCUMC_Status_Report_Fields::DUE_DATE,
		'meta_query'      => array(
			'key'         => RCUMC_Status_Report_Fields::DUE_DATE,
			'compare'     => 'BETWEEN',
			'value'       => array( $start_date, $end_date ),
			),
		);

	return rcumc_status_report_query_orderby_last_and_first_name( $args );

}   // end of rcumc_status_report_by_week_content()


function rcumc_status_report_by_name_content( $person_id_text )
{
	$person_id = (int) $person_id_text;

	if ( $person_id == -1 )
	{
		// No Person selected, list everyone.
		return rcumc_status_report_default_content();
	}

	$args = array(
		'post_type'       => RCUMC_Status_Report::TYPE_NAME,
		'posts_per_page'  => 8,
		'post_status'     => 'publish',
		'order'           => 'DESC',
		'orderby'         => 'meta_value_num',
		'meta_key'        => RCUMC_Status_Report_Fields::DUE_DATE,
		'meta_query'      => array(
			'key'         => RCUMC_Status_Report_Fields::PERSON_ID,
			'compare'     => '=',
			'value'       => $person_id_text,
			),
		);

	// We can skip the query order by name because this query should
	// only produce a single name.
	return new WP_Query( $args );

}   // end of rcumc_status_report_by_name_content()


function rcumc_status_report_by_date_range( $start_date_text, 
											$stop_date_text )
{
	$GLOBALS['rcumc-start-date-range'] = $start_date_text;
	$GLOBALS['rcumc-stop-date-range']  = $stop_date_text;

	$start_date = strtotime( $start_date_text );
	$stop_date  = strtotime( $stop_date_text  );

	// Place the earlier date as the first first argument passed to the
	// meta-query and the larger one as the second.
	if ( $start_date < $stop_date )
	{
		$beginning = $start_date;
		$ending    = $stop_date;
	}
	else
	{
		$beginning = $stop_date;
		$ebding    = $start_date;
	}

	// Make sure the first date starts at midnight and the second date
	// ends one second before the next midnight to insure that we
	// ignore the hours, minutes seconds part of the timestamp.
	$beginning_day = getdate( $beginning );
	$ending_day    = getdate( $ending );

    $start_day = mktime( 0, 0, 0, 
						 $beginning_day['mon'], 
						 $beginning_day['mday'],
						 $beginning_day['year'] );


    $end_day   = mktime(23,59,59, 
						 $ending_day['mon'], 
						 $ending_day['mday'],
						 $ending_day['year'] );

    $meta_args = array( $start_day, $end_day );


	$args = array(
		'post_type'       => RCUMC_Status_Report::TYPE_NAME,
		'posts_per_page'  => 8,
		'post_status'     => 'publish',
		'order'           => 'DESC',
		'orderby'         => 'meta_value_num',
		'meta_key'        => RCUMC_Status_Report_Fields::DUE_DATE,
		'meta_query'      => array(
			'key'         => RCUMC_Status_Report_Fields::DUE_DATE,
			'compare'     => 'BETWEEN',
			'value'       => $meta_args,
			),
		);

	return rcumc_status_report_query_orderby_last_and_first_name( $args );

}   // end of rcumc_status_report_by_date_range()



function rcumc_status_report_query_orderby_last_and_first_name( $args )
{
	add_filter('posts_join_paged',
			   'rcumc_status_report_join_last_and_first_names',
			   10, 1);
	add_filter('posts_orderby', 
			   'rcumc_status_report_order_by_last_and_first_name',
			   10, 1);
	$query = new WP_Query( $args );
	remove_filter('posts_join_paged',
				  'rcumc_status_report_join_last_and_first_names');
	remove_filter('posts_orderby', 
				  'rcumc_status_report_order_by_last_and_first_name');
	
		return $query;

	}   // end of rcumc_status_report_query_orderby_last_and_first_name()

