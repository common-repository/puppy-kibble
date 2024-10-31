<?php
/*
Plugin Name: Puppy Kibble
Plugin URI: http://github.com/chrismccoy/puppykibble
Description: A widget to show random puppy images
Version: 1.0
Author: Chris McCoy
Author URI: http://github.com/chrismccoy
*/

/* Cache Puppy Pics on Activation */

register_activation_hook( __FILE__, 'puppykibble_activation' );

/* Sets transient for cached puppy pics */

function puppykibble_activation() {
	if ( false === ( $puppy = get_transient( '_puppykibble' ) ) ) {
 		$puppy = file(plugin_dir_path( __FILE__ ) . 'db/puppy.txt');
     		set_transient( '_puppykibble', $puppy, YEAR_IN_SECONDS * 100);
	}
}

/* Loads and registers the new widget. */
add_action( 'widgets_init', 'puppykibble_register_widget' );

function puppykibble_register_widget() {
	register_widget( 'PuppyKibble_Widget' );
}

/**
 * PuppyKibble Widget Class
 *
 * Show Random Puppy Images
 */

class PuppyKibble_Widget extends WP_Widget {

	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 1.0
	 */
	function __construct() {

		/* Set up the widget options. */
		$widget_options = array(
			'classname' => 'puppykibble-widget',
			'description' => esc_html__( 'A widget that shows random puppy images.', 'puppykibble' )
		);

		/* Set up the widget control options. */
		$control_options = array(
			'id_base' => "widget-puppykibble"
		);

		/* Create the widget. */
		parent::__construct( "widget-puppykibble", esc_attr__( 'Puppy Kibble', 'puppykibble' ), $widget_options, $control_options );

	}

	/**
	 * Grabs puppy results from transient
	 * @since 1.0
	 */
	function load_puppy_data() {

		$puppy = get_transient( '_puppykibble' );
		$kibble = rand(0, sizeof( $puppy )-1);
		$content = '<img src="' . $puppy[$kibble] . '">';
		return $content;
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 * @since 1.0
	 */
	function widget( $args, $instance ) {
		extract( $args );

    $title 	= apply_filters('widget_title', $instance['title']);

		/* Output the theme's $before_widget wrapper. */
		echo $before_widget;

		/* If a title was input by the user, display it. */
		if ( !empty( $instance['title'] ) )
			echo $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, 'puppykibble' ) . $after_title;

		/* Display random puppy image using the cached db of urls */
		echo $this->load_puppy_data();

		/* Close the theme's widget wrapper. */
		echo $after_widget;
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 * @since 1.0
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 * @since 1.0
	 */
	function form( $instance ) {

		/* Set up the default form values. */
		$defaults = array(
			'title' => 'Random Puppy'
		);

		/* Merge the user-selected arguments with the defaults. */
		$instance = wp_parse_args( (array) $instance, $defaults );

        	$title = esc_attr($instance['title']);

		?>

  		<p>
          		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title:', 'puppykibble'); ?></label>
          		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        	</p>

        <?php
	}
}
