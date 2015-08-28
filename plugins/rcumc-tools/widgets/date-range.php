<?php
/**
 * Widget to select a start and end date.
 */


add_action('widgets_init', 'rcumc_add_date_range_widget');

function rcumc_add_date_range_widget()
{
	register_widget('RCUMC_Date_Range');
}

class RCUMC_Date_Range extends WP_Widget
{
	public function __construct()
	{
		$widget_options = array(
			'classname'    => 'rcumc_tools',
			'description'  => 'Date Range Selector',
			);

		parent::__construct(
			'rcumc_date_range',
			'Date Range Selector',
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

		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style(  'jquery-ui-style',
						   'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/smoothness/jquery-ui.css', true);

		if ( isset($GLOBALS['rcumc-start-date-range']) )
		{
			$start_date_value = date(
				'd-M-Y',
				strtotime( $GLOBALS['rcumc-start-date-range'] ) );
		}
		else
		{
			// Use now
			$start_date_value = date('d-M-Y');
		}

		if ( isset($GLOBALS['rcumc-stop-date-range']) )
		{
			$stop_date_value = date(
				'd-M-Y',
				strtotime( $GLOBALS['rcumc-stop-date-range'] ) );
		}
		else
		{
			// Use now
			$stop_date_value = date('d-M-Y');
		}

		if ( !isset( $instance['start_label'] )  ||
			'' == $instance['start_label'] )
		{
			$instance['start_label'] = 'Start of Date Range';
		}

		if ( !isset( $instance['stop_label'] ) ||
			'' == $instance['stop_label'] )
		{
			$instance['stop_label'] = 'End of Date Range';
		}

?>
		<script>
			 jQuery(document).ready(function() {
					 jQuery('#start-date').datepicker( {
						 dateFormat : 'dd-M-yy',
						 changeYear : true,
						 changeMonth : true
								 });
		 } );

			 jQuery(document).ready(function() {
					 jQuery('#stop-date').datepicker( {
						 dateFormat : 'dd-M-yy',
						 changeYear : true,
						 changeMonth : true
								 });
		 } );
	 </script>
		   <form>
		   <label><?php echo $instance['start_label']; ?>:<br/>
		<input type="text" id="start-date" name="start-date"
			 value="<?php echo $start_date_value; ?>"   />
		   </label>
<p>
		<label><?php echo $instance['stop_label']; ?>:<br/>
		<input type="text" id="stop-date" name="stop-date"
			 value="<?php echo $stop_date_value; ?>"   />
		   </label>
</p>
		   <input type="submit" value="Go" /> 

		   </form>

<?php

		echo $after_widget;
	}   // end of widget()

	// Options that the developer sets from the dashboard.
	public function form ($instance )
	{
		// Setup default widget settings:
		$defaults = array(
			'title'        => __('Date Range Selector', RCUMC_TOOLS_TD),
			'start_label'  => __('Start of Date Range', RCUMC_TOOLS_TD),
			'stop_label'   => __('End of Date Range',   RCUMC_TOOLS_TD),
			);

		// set defaults when not already set.
		$instance = wp_parse_args( (array) $instance, $defaults );

?>
		<p>
		<label>Title:<br/>
		<input  type="text"
				id="<?php    echo $this->get_field_id('title'); ?>"
				name="<?php  echo $this->get_field_name('title'); ?>"
				value="<?php echo $instance['title']; ?>" />
        </label>
        </p>
		<p>
		<label>Label for Start of Date Range:<br/>
		<input  type="text"
				id="<?php echo $this->get_field_id('start_label'); ?>"
                name="<?php echo $this->get_field_name('start_label'); ?>"
                value="<?php echo $instance['start_label']; ?>" />
        </label>
        </p>
		<p>
		<label>Label for End of Date Range:<br/>
		<input  type="text"
				id="<?php echo $this->get_field_id('stop_label'); ?>"
                name="<?php echo $this->get_field_name('stop_label'); ?>"
                value="<?php echo $instance['stop_label']; ?>" />
		</label>
        </p>

<?php

	}   // end of form()

	// Update from processing form() results.
	public function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;

		$instance['title']       = strip_tags( $new_instance['title'] );
		$instance['start_label'] = strip_tags( $new_instance['start_label'] );
		$instance['stop_label']  = strip_tags( $new_instance['stop_label'] );

		return $instance;

	}   // end of update()

}   // end of class RCUMC_Status_Report_By_Week
