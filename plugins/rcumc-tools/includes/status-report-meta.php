<?php
 
/**
 * Weekly Status Report Meta Fields.
 */

require_once('constants.php');

/**
 * Create a Meta Box for RCUMC Status Report custom post type.
 *
 * This metabox manages the metadata associated with the status report.
 */

function rcumc_create_status_report_metabox()
{
	add_meta_box(
		RCUMC_Status_Report::METABOX_NAME,           // ID
		__('Weekly Status Report', RCUMC_TOOLS_TD),  // Title
		'rcumc_status_report_metabox_field',         // Display Callback
		RCUMC_Status_Report::TYPE_NAME,              // Post Type
		'normal',                                    // Context
		'high',                                      // Priority
		null                                         // Callback Args
		);

	return;

}   // end of rcumc_create_status_report_metabox()


/**
 * Fills in/generates the form used to enter metadata for a Status Report.
 *
 * This function takes existing metadata, if it exists, and packages
 * it up in an HTML Form that is displayed when creating a new 
 * RCUMC Status Report or editing an existing one.
 */

function rcumc_status_report_metabox_field( $post )
{
	// Get existing values for fields.  If they do not exist, the values
	// should be either false or ''.

	$title      = $post->post_title;

	$due_date   = get_post_meta($post->ID, 
								RCUMC_Status_Report_Fields::DUE_DATE, true);
	$person     = get_post_meta($post->ID, 
								RCUMC_Status_Report_Fields::PERSON_ID, true);
	$done_text  = get_post_meta($post->ID, 
								RCUMC_Status_Report_Fields::DONE_TEXT, true);
	$todo_text  = get_post_meta($post->ID, 
								RCUMC_Status_Report_Fields::TODO_TEXT, true);
	$help_text  = get_post_meta($post->ID, 
								RCUMC_Status_Report_Fields::HELP_TEXT, true);

	$status = get_post_status();
    $state  = ( ($status == 'published') ? 'Submitted' : 'In Progress' );

	if ( ! $person )
	{
		$person = get_current_user_id();
	}
	$user_name = rcumc_get_full_name_by_id( $person );


	$editor_args = array(
		'media_buttons'   => false,
		'textarea_rows'   => 8,
		);

	// Make sure that the Due Date has a value.  (not false or '').
	$due_date_set = true;
	if ( ! $due_date || '' == $due_date )
	{
		$due_date = time();
		$due_date_set = false;
	}

	
	$due_date_text = ( $due_date_set ? date('d-M-Y', $due_date) : '');

    //
	if ( '' == $title )
	{
		$title = rcumc_generate_status_report_title ( 
			$person, ($due_date_set ? $due_date : false) );
	}

	// Add Datepicker to Due Date Input Field.
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_style(  'jquery-ui-style',
					   'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/smoothness/jquery-ui.css', true);
?>

<p>
	 <script>
	 jQuery(document).ready(function() {
			 jQuery('#due_date_field').datepicker( {
				 dateFormat : 'dd-M-yy'
						 });
		 } );
	 </script>

	 <h1><?php echo $title; ?></h1>
	 <p>&nbsp;</p>
	 <hr style="border-top: 5px solid purple;" />
	 <label><strong>Due Date:&nbsp;</strong>
	 <input type="text" id="due_date_field"
			name="<?php echo RCUMC_Status_Report_Fields::DUE_DATE; ?>"
			value="<?php echo $due_date_text; ?>" 
									size="12">
		  </label>

	 <p>
	 <hr style="border-top: 5px solid purple;"/>
	 <label><h2>Did Last Week:</h2>
     <?php
	   wp_editor( $done_text,
				  RCUMC_Status_Report_Fields::DONE_TEXT,
				  $editor_args );
      ?>
	</label>
     </p>
				  	 <p>&nbsp;</p>
				  	   <p>

		  <hr style="border-top:5px solid purple;"/>
	 
		  <label><h2>Plans for next Week:</h2>
     <?php
	   wp_editor( $todo_text,
				  RCUMC_Status_Report_Fields::TODO_TEXT,
				  $editor_args );
      ?>
	</label>
     </p>
     	 <p>&nbsp;</p>
		  <p>
		  
<hr style="border-top: 5px solid purple;"/>
	 
		  <label><h2>Collaberation Requests:</h2>
     <?php
	   wp_editor( $help_text,
				  RCUMC_Status_Report_Fields::HELP_TEXT,
				  $editor_args );
      ?>
	</label>
     </p>
<br/>

		  <input type="hidden"  
		         name="<?php echo RCUMC_Status_Report_Fields::PERSON_ID; ?>"
		         value="<?php echo $person ?>" />

		  <?php
		  	wp_nonce_field( plugin_basename(__FILE__), 
					RCUMC_Status_Report_Fields::NONCE_FIELD );
	        ?>

</p>

<?php

    // BEGIN Debug
//   $post_type = get_post_type_object( $post->post_type );
//   echo "RCUMC Status Report Post Type Object:<br/>" . PHP_EOL;
//   print_r($post_type);
//   echo  "<br/>" . PHP_EOL;
//
//    $user = wp_get_current_user();
//    echo "Current User Object:<br/>" . PHP_EOL;
//    print_r($user);
//    echo  "<br/>" . PHP_EOL;
    // END Debug
}   // end of rcumc_sr_metabox_field()

/**
 * Utility Function that builds a user's full name starting with just
 * their User ID.
 */

function  rcumc_get_full_name_by_id( $user_id )
{
	$user = new WP_User( $user_id );
	$name = $user->first_name . ' ' . $user->last_name;

	return $name;
}

/**
 * Utility Function that builds the Status Report Title based on 
 * the User ID and [report] Due Date.
 */
function  rcumc_generate_status_report_title( $user_id, $due_date = false )
{
	$user_name     = rcumc_get_full_name_by_id( $user_id );

	if ( $due_date )
	{
		$due_date_text = rcumc_get_due_date_text( $due_date );

		$title = sprintf(
			__('Status Report by %1$s for Week of %2$s', 
			   RCUMC_TOOLS_TD),
			$user_name, $due_date_text);
	}
	else
	{
		$title = sprintf(
			__('Status Report by %1$s', RCUMC_TOOLS_TD),
			$user_name);
	}

	return $title;
}

/**
 * Converts a binary Linux Timestamp into a text string.
 */
function rcumc_get_due_date_text( $due_date )
{
	return date('l, F j, Y', $due_date);
}

/**
 * Converts a date text string into a binary Linux Timestamp.
 * 
 * Any valid time string should work.  If no text string is provided,
 * a Zero timestamp is returned.
 */
function rcumc_due_date_from_string( $due_date_text)
{
	if ( '' == $due_date_text )
	{
		// No Date provided.
		$timestamp = 0;
	}
	else
	{
		$timestamp = strtotime( $due_date_text );
	}

	return $timestamp;
}

/**
 * This function is a wp_insert_post_data filter, before a post is saved.
 *
 * This function only changes the post_title on RCUMC Status Report types.
 */

function  rcumc_status_report_set_title( $data, $postarr )
{
	if ( $data['post_type'] != RCUMC_Status_Report::TYPE_NAME )
	{
		// This is not the type we are looking for.
		return $data;
	}

	// Get the Person filing the report.  This should be in the $_REQUEST
	// array if this is coming from an edit page.  Note that the person
	// is not available thought the meta data because the meta data is
	// set after the post is set and this function is called before
	// the post is saved.
	//
	// Default to the Current User.

	$user_id =  (isset($_REQUEST[RCUMC_Status_Report_Fields::PERSON_ID] )) ?
		$_REQUEST[RCUMC_Status_Report_Fields::PERSON_ID] :
		get_current_user_id();

	$user_name = rcumc_get_full_name_by_id( $user_id );

	// Get the Due Date for this report.  This should be found in the
	// $_REQUEST array if this is coming from an edit page.  Note that the
	// due date is not available through the meta data for this Post ID
	// because the meta data has not been set yet.
	//
	// Default:  Current time.
	if ( isset($_REQUEST[RCUMC_Status_Report_Fields::DUE_DATE] ) )
	{
		$due_date_text = $_REQUEST[RCUMC_Status_Report_Fields::DUE_DATE];
		$due_date      = rcumc_due_date_from_string($due_date_text);

		$data['post_title'] = 
			rcumc_generate_status_report_title(	$user_id, $due_date );
	}

	return $data;

}   // end of rcumc_status_report_set_title()


/**
 * Save Meta Data for rcumc_status_report Custom Post Type.
 *
 * This function uses the $_REQUEST object instead of the $_POST so that
 * it will also work if the form does a GET.
 *
 * Called for the action:  save_post_rcumc_status_report
 * This means that we do not need to check the post_type to insure
 * that we are operating on the correct custom post type.
 *
 * For some unknown reason [to me], this function is being called
 * twice during a save_post action.  The first time it is called
 * the $_REQUEST does not contain any form data and therefore exits
 * during the NONCE test.  The second time it is called $_REQUEST contains
 * the expected data and processes normally.
 */
function  rcumc_status_report_meta_save( $post_id, $post, $update )
{
	// Verify this came from the screen and with proper authorization,
	// because save_post can be triggered at other times.
	//
	// Note: This tst is first because this function is called twice during
	//       the save_post action.  The first time we need to exit without
	//       processing and we can tell it is the first time because
	//       the $_REQUEST object does not contain the NONCE field.
	//
	if ( !isset( $_REQUEST[RCUMC_Status_Report_Fields::NONCE_FIELD] ) ||
		 !wp_verify_nonce( $_REQUEST[RCUMC_Status_Report_Fields::NONCE_FIELD],
						   plugin_basename(__FILE__) ) )
	{
		return $post_id;
	}

	// If this save is BOTH a revision AND an autosave, we can skip 
	// saving the meta data at this time.  This function will be called
	// again when it is time to actually save the data.
//	if ( !( wp_is_post_revision( $post_id ) &&
//			! wp_is_post_autosave( $post_id ) ) )
//	{
//		add_post_meta( $post_id, 'Debug-1a', 'Doing AutoSave', false);
//
//		return $post_id;
//	}


	// Process Post

	// NOTE: $post->post_type is a string value, $post_type is an object.
	$post_type = get_post_type_object( $post->post_type );

	// Verify that user has permission to edit the post.
	if ( !current_user_can( $post_type->cap->edit_posts ) )   //, $post_id ) )
	{
		return $post_id;
	}

	// Get Due Date and convert it from DD-MMM-YYYY format into
	// UNIX Timestamp format (binary number of seconds 
	// since 1/1/1970 00:00:00 UTC
	if ( isset($_REQUEST[RCUMC_Status_Report_Fields::DUE_DATE]) )
	{
		// Due Date Text is a UNIX Timestamp in string form.
		$due_date_text  = $_REQUEST[RCUMC_Status_Report_Fields::DUE_DATE];
		$due_date       = rcumc_due_date_from_string($due_date_text);
	}
	else
	{
		$due_date = rcumc_due_date_from_string('');
	}

//	$request = print_r($_REQUEST, true);
//	add_post_meta($post_id, 'Debug-Request', $request);

	// Setup an array so that processing of each meta value can be
	// handled in a loop, for new, update or delete actions.
	$metadata = array(
		RCUMC_Status_Report_Fields::DUE_DATE    => $due_date, 

		RCUMC_Status_Report_Fields::PERSON_ID   =>  
		         isset($_REQUEST[RCUMC_Status_Report_Fields::PERSON_ID]) ?
		         $_REQUEST[RCUMC_Status_Report_Fields::PERSON_ID] : 0,

		RCUMC_Status_Report_Fields::DONE_TEXT    =>  
		         isset($_REQUEST[RCUMC_Status_Report_Fields::DONE_TEXT]) ?
  		         $_REQUEST[RCUMC_Status_Report_Fields::DONE_TEXT] : '',

		RCUMC_Status_Report_Fields::TODO_TEXT    =>
		         isset($_REQUEST[RCUMC_Status_Report_Fields::TODO_TEXT]) ?
  		         $_REQUEST[RCUMC_Status_Report_Fields::TODO_TEXT] : '',

		RCUMC_Status_Report_Fields::HELP_TEXT    =>
		         isset($_REQUEST[RCUMC_Status_Report_Fields::HELP_TEXT]) ?
  		         $_REQUEST[RCUMC_Status_Report_Fields::HELP_TEXT] : '',

		);

	// Save user first and last names.  This is unnormalized data. 
	// It is being saved in the metadata so that we can use it to sort
	// status reports by user name.
	if ( $metadata[RCUMC_Status_Report_Fields::PERSON_ID] != 0 )
	{
		$user = new WP_User($metadata[RCUMC_Status_Report_Fields::PERSON_ID]);
		
		$metadata[RCUMC_Status_Report_Fields::FIRST_NAME] =  $user->first_name;
		$metadata[RCUMC_Status_Report_Fields::LAST_NAME]  =  $user->last_name;
	}

	// Loop for saving the meta values.  A different function is 
	// required if the data is new, changed or deleted.
	foreach( $metadata  as  $key => $value )
	{
		rcumc_store_meta_data( $post_id, $key, $value );
	}

	// All Done
	return $post_id;

}   // end of rcumc_status_report_meta_save()

/**
 * Utility function to store Meta Data.
 *
 * The function to actually save or store the meta value depends on
 * whether it is a new meta value (no previous value existed), is an
 * update of a previously existing value, or is deleting a previously
 * existing value.
 */
function  rcumc_store_meta_data( $post_id, $key, $value )
{
	// Get current value of meta key.  
	// Only 1, in the form of a string (no arrays involved).
	$current_value = get_post_meta( $post_id, $key, true );
	
	$no_old_value  = '' == $current_value;
	$no_new_value  = '' == $value;
	
	if ( $no_new_value && $no_old_value )
	{
		// New value
		add_post_meta( $post_id, $key, $value, true );
	}
	elseif ( !$no_new_value && $value != $current_value )
	{
		// The value has been changed.
		update_post_meta( $post_id, $key, $value );
	}
	elseif ( $no_new_value && !$no_old_value )
	{
		// Value was removed.
		delete_post_meta( $post_id, $key, $current_value );
	}

	return;

}   // end of rcumc_store_meta_data()


/**
 * Filter to include Metadata Last Name and First Name so that they
 * are available for sorting purposes.
 *
 * This filter works by doing two LEFT JOINs with the postmeta table.
 * The first join finds the last name metavalue and the second join
 * finds the first name metavalue.
 * 
 * This filter works with the filter
 * rcumc_status_report_order_by_last_and_first_name() by adding the JOINs
 * to the SQL SELECT statement being built by WP_QUERY constructor, and
 * by aliasing the postmeta table with the aliases lastvalue and firstvalue.
 *
 * This filter needs to be added just before the query is performed and
 * removed immediately afterwards to insure that this filter does not
 * affect any other query.
 */
function rcumc_status_report_join_last_and_first_names( $join )
{
	global $wpdb;

	// We want to perform a LEFT JOIN twice to include the metadata
	// containing the last name and first name fields.

	$metatable = $wpdb->prefix . 'postmeta';
	$posttable = $wpdb->prefix . 'posts';

	$last_join = ' LEFT JOIN ' . $metatable . ' lastvalue ON ' .
		$posttable . '.ID = lastvalue.post_id AND ' .
        'lastvalue.meta_key = "' .
        RCUMC_Status_Report_Fields::LAST_NAME . '" ';

	$first_join = ' LEFT JOIN ' . $metatable . ' firstvalue ON ' .
		$posttable . '.ID = firstvalue.post_id AND ' .
        'firstvalue.meta_key = "' .
        RCUMC_Status_Report_Fields::FIRST_NAME . '" ';

	return $join . $last_join . $first_join;

}   // end of rcumc_status_report_join_last_and_first_names

/** 
 * Filter to order by last_name, first_name in ASC order.
 *
 * This filter works by modifying the ORDER BY clause in the SQL SELECT
 * statement being built by the WP_Query constructor.  It appends the
 * Last Name column and the First Name column of meta_value from the 
 * postmeta table using the table aliases setup by the filter
 * rcumc_status_report_join_last_and_first_names().
 *
 * This filter needs to be added just before the query is performed and
 * removed immediately afterwards to insure that this filter does not
 * affect any other query.
 */
function rcumc_status_report_order_by_last_and_first_name( $orderby )
{
	// Append to existing order by clause.
	$clause = ( !empty($orderby) && '' != $orderby ) ? ($orderby . ', ') : '';

	return $clause . 'lastvalue.meta_value, firstvalue.meta_value';

}   // end of rcumc_status_report_order_by_last_and_first_name()