<?php
/**
 * Plugin Name: Snooth Widget
 * Plugin URI: http://github.com/bobbymarko/Snooth-Wordpress-Widget/
 * Description: Widget displays content from Snooth.com. Based on example widget from : http://justintadlock.com/archives/2009/05/26/the-complete-guide-to-creating-widgets-in-wordpress-28
 * Version: 0.1
 * Author: Bobby Marko
 * Author URI: http://bobby-marko.com
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'snooth_load_widgets' );

/**
 * Register our widget.
 * 'Snooth_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function snooth_load_widgets() {
	register_widget( 'Snooth_Widget' );
}

/**
 * Snooth Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class Snooth_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Snooth_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'snooth', 'description' => __('Present your wine reviews from Snooth.com', 'snooth') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'snooth-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'snooth-widget', __('Snooth Widget', 'snooth'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$userID = $instance['userID'];
		$hashPath = $instance['hashPath'];
		$wineCount = $instance['wineCount'];
		
		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
		?>
		<!-- Start Snooth Widget -->

	    <script language='javascript'>
		<!--
			var snooth_userID = <?php echo $userID; ?>;
			var snooth_hashPath = '<?php echo $hashPath; ?>';
			var snooth_syndicationContent = 'ratings';
			var snooth_wineCount = <?php echo $wineCount ?>;
			function snooth_blogWines(snooth_wines) {
			  var snooth_wineBlock = '<div style="width: 160px; padding: 3px; border: 1px solid #ddd; background: #fff; font: normal 10px \'Lucida Grande\', Arial, sans-serif; color: #666; line-height: 13px;"><div style="padding: 7px 7px 31px 7px; border: 1px solid #ddd; background: #eee url(http://www.snooth.com/template/images/snooth_widget_bg.gif) right bottom no-repeat; ">';
			  var snooth_wine;
			  var snooth_count = 0;
			  for(var snooth_wineIdx in snooth_wines) {
			    if(snooth_count >= snooth_wineCount) {
			        break;
			    }
			    snooth_count++;
			    snooth_wine = snooth_wines[snooth_wineIdx];
			    snooth_wineBlock += '<a href="' + snooth_wine['link'] + '" style="font: bold 11px \'Lucida Grande\', Arial, sans-serif; text-decoration: none; color: #aa385d; line-height: 13px; border-bottom: 1px solid #bfd69b;">' + snooth_wine['name'] + '</a> (' + snooth_wine['vintage'] + ')';
			    snooth_wineBlock += '<dl style="margin: 6px 0 8px 0; padding: 0 0 9px 0; font: normal 11px \'Lucida Grande\', Arial, sans-serif; border-bottom: 1px solid #ddd;">';
			    snooth_wineBlock += '<dt style="float: left; width: 85px; margin: 0; padding: 0; color: #777; font-weight: bold; color: #476d29;">SnoothRank:</dt><dd style="float: left; width: 59px; margin: 0; padding: 0; color: #333; font-weight: bold;">' + snooth_wine['snoothrank'] + '</dd>';
			    snooth_wineBlock += '<dt style="float: left; width: 85px; margin: 0; padding: 0; color: #777;">My Rating:</dt><dd style="float: left; width: 59px; margin: 0; padding: 0; color: #333;">' + snooth_wine['myRating'] + '\5</dd>';
			    if(snooth_wine['price']) {
				snooth_wineBlock += '<dt style="float: left; width: 85px; margin: 0; padding: 0; color: #777;">Price:</dt><dd style="float: left; width: 59px; margin: 0; padding: 0; color: #333;">$' + snooth_wine['price'] + '</dd>';
			    }
			    snooth_wineBlock += '<div style="height: 0; margin: 0; line-height: 0; clear: both;"></div></dl>';
			  }
			  snooth_wineBlock += '</div></div></div>';
			  document.getElementById('snooth_winePlaceholder').innerHTML = snooth_wineBlock;
			}
		-->
		</script>
		<div id="snooth_winePlaceholder"></div>
		<script language="javascript" src="http://www.snooth.com/template/js/wine-data.js"></script>

		<?php
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['userID'] = strip_tags( $new_instance['userID'] );
		$instance['hashPath'] = strip_tags( $new_instance['hashPath'] );
		$instance['wineCount'] = strip_tags( $new_instance['wineCount'] );

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Snooth', 'example'), 'userID' => __('292883', 'example'), 'hashPath' => '1/4/0/', 'wineCount' => '3' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

		<!-- Your userID: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'userID' ); ?>"><?php _e('Your userID:', 'example'); ?></label>
			<input id="<?php echo $this->get_field_id( 'userID' ); ?>" name="<?php echo $this->get_field_name( 'userID' ); ?>" value="<?php echo $instance['userID']; ?>" style="width:100%;" />
		</p>
		
		<!-- Your hashPath: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'hashPath' ); ?>"><?php _e('Your Hash Path:', 'example'); ?></label>
			<input id="<?php echo $this->get_field_id( 'hashPath' ); ?>" name="<?php echo $this->get_field_name( 'hashPath' ); ?>" value="<?php echo $instance['hashPath']; ?>" style="width:100%;" />
		</p>
		
		<!-- Your wineCount: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'wineCount' ); ?>"><?php _e('Number of reviews to show:', 'example'); ?></label>
			<input id="<?php echo $this->get_field_id( 'wineCount' ); ?>" name="<?php echo $this->get_field_name( 'wineCount' ); ?>" value="<?php echo $instance['wineCount']; ?>" style="width:100%;" />
		</p>

	<?php
	}
}

?>