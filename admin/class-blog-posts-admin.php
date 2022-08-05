<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://blogs.local/
 * @since      1.0.0
 *
 * @package    Blog_Posts
 * @subpackage Blog_Posts/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Blog_Posts
 * @subpackage Blog_Posts/admin
 * @author     Tanzeel Ahmed <tanzeel@wpminds.com>
 */
class Blog_Posts_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Blog_Posts_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Blog_Posts_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/blog-posts-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Blog_Posts_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Blog_Posts_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/blog-posts-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function wpb_settings_page() {
							
		$page_title = 'WP Blog Settings page';
		$menu_title = 'WP Blog Posts Settings';
		$capability = 'manage_options';
		$slug 		= 'wp-blog-settings-page';
		$callback 	= array( $this, 'wpb_plugin_settings_page' );
		$icon 		= 'dashicons-admin-settings';
		$position 	= 40;
		
		add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);	
	}
	function wpb_plugin_settings_page() {
		?>
		<h1> <?php esc_html_e( 'Welcome to WP Blog Posts Settings page', 'my-plugin-textdomain' ); ?> </h1>
		<form method="POST" action="options.php">
		<?php
		settings_fields( 'wpb-settings-page' );
		do_settings_sections( 'wpb-settings-page' );
		submit_button();
		?>
		</form>
		<?php
	}
	function wpb_settings_tab() {

		add_settings_section(
			'wpb_settings_section',
			__( 'WP Blog Posts Setting fields', 'my-textdomain' ),
			array( $this,'my_setting_section_callback_function'),
			'wpb-settings-page'
		);
		add_settings_field(
			'wpb_settings_field_1',
			__( 'Manage number of Blogs per Page.', 'my-textdomain' ),
			array( $this,'wpb_settings_field_callback_function_1'),
			'wpb-settings-page',
			'wpb_settings_section'
		);
		add_settings_field(
			'wpb_settings_field_2',
			__( 'Display Blog posts by order.', 'my-textdomain' ),
			array( $this,'wpb_settings_field_callback_function_2'),
			'wpb-settings-page',
			'wpb_settings_section'
		);
		add_settings_field(
			'wpb_settings_field_3',
			__( 'Display Blogs on category selection.', 'my-textdomain' ),
			array( $this,'wpb_settings_field_callback_function_3'),
			'wpb-settings-page',
			'wpb_settings_section'
		);
		register_setting( 'wpb-settings-page', 'wpb_settings_field_1' );
		register_setting( 'wpb-settings-page', 'wpb_settings_field_2' );
		register_setting( 'wpb-settings-page', 'wpb_settings_field_3' );
	}
	function my_setting_section_callback_function() {
							
	}
	function wpb_settings_field_callback_function_1() {
		?>
		<label for="my-input"><?php _e( 'Number of Blogs:' ); ?></label>
		<input type="number" id="wpb_settings_field_1" name="wpb_settings_field_1"  placeholder="Enter numbers" value="<?php echo get_option( 'wpb_settings_field_1' ); ?>">
		<?php					
	}
	function wpb_settings_field_callback_function_2() {
		?>
		<label for="blogs-by-order"><?php _e( 'Order by:' ); ?></label>
		<select name="blogs-by-order" id="blogs-by-order">
			<option>Select Order</option>
			<option value="<?php echo get_option( 'wpb_settings_field_2' ); ?>">Ascending</option>
			<option value="<?php echo get_option( 'wpb_settings_field_2' ); ?>">Descending</option>
		</select>
		<?php					
	}
	function wpb_settings_field_callback_function_3() {
		?>
		<label for="blog-categories"><?php _e( 'Select:' ); ?></label>
		<select name="blog-categories" id="blog-categories">
			<option value="">Select Categories</option>
			<?php
				// Get all the categories
				$categories = get_terms( 'category' );

				// Loop through all the returned terms
				foreach ( $categories as $category ):
				?>
			<option value="<?php echo get_option( 'wpb_settings_field_3' ); ?>"><?php echo $category->name ?></option>
			<?php
			// end the loop
			endforeach;	
			?>
		</select>
		<?php				
	}
}