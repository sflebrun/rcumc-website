<?php
/**
 * Widget to select a week of Status Reports.
 */


add_action('widgets_init', 'rcumc_add_status_report_by_week_widget');

function rcumc_add_status_report_by_week_widget()
{
	register_widget('RCUMC_Status_Report_By_Week');
}

class RCUMC_Status_Report_By_Week extends WP_Widget
{
	public function __construct()
	{
		$widget_options = array(
			'classname'    => 'status-reports',
			'description'  => 'Lists Status Reports for a selected week.',
			);

		parent::__construct(
			'status-report-by-week',
			'Status Reports by week',
			$widget_options
			);

	}   // end of __construct()

	// What is displayed on the front end page to the user.
	public function widget( $args, $instance )
	{
		extract( $args );

		$title = apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		if ( $title )
		{
			echo $before_title . $title . $after_title;
		}

		wp_enqueue_script( RCUMC_Tools::SUBMIT_ONCHANGE );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style(  'jquery-ui-style',
						   'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/smoothness/jquery-ui.css', true);

		if ( isset($GLOBALS['rcumc-status-report-by-week-date']) )
		{
			$due_date_value = date(
				'd-M-Y',
				strtotime( $GLOBALS['rcumc-status-report-by-week-date'] ) );
		}
		else
		{
			// Use now
			$due_date_value = date('d-M-Y');
		}

?>
		<script>
			 jQuery(document).ready(function() {
					 jQuery('#due-date').datepicker( {
						 dateFormat : 'dd-M-yy',
						 changeYear : true,
								 changeMonth : true
								 });
		 } );
	 </script>
		   <form>
		   <label>Select any Day in Week to Display:
		<input type="text" id="due-date" name="due-date"
			 value="<?php echo $due_date_value; ?>"
		   onchange="rcumc_tools_submit_onchange(this);"  />
		   </label>
<!-- 		   <input type="submit" value="Go" />   -->

		   </form>

<?php

		echo $after_widget;
	}   // end of widget()

	// Options that the developer sets from the dashboard.
	public function form ($instance )
	{
		// Setup default widget settings:
		$defaults = array(
			'title'        => __('Status Reports by Week', RCUMC_TOOLS_TD),
			);

		// set defaults when not already set.
		$instance = wp_parse_args( (array) $instance, $defaults );

?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
		<input  type="text"
				id="<?php  echo $this->get_field_id('title'); ?>"
				name="<?php echo  $this->get_field_name('title'); ?>"
				value="<?php echo $instance['title']; ?>" />


<?php

	}   // end of form()

	// Update from processing form() results.
	public function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;

	}   // end of update()

}   // end of class RCUMC_Status_Report_By_Week
