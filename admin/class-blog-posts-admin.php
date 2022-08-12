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
		<div class="wrap">
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "wporg"
            settings_fields( 'wpb_settings_page' );
            // output setting sections and their fields
            // (sections are registered for "wporg", each field is registered to a specific section)
            do_settings_sections( 'wpb_settings_page' );
            // output save settings button
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
		<?php
	}
	function wpb_settings_tab() {
		register_setting( 'wpb_settings_page', 'wpb_settings_options' );

		add_settings_section(
			'wpb_settings_section',
			'WP Blog Posts Setting fields',
			'',
			'wpb_settings_page'
		);
		add_settings_field(
			'wpb_posts_per_page_field',
			'Manage number of Blogs per Page.',
			array( $this,'wpb_settings_field_callback_function_1'),
			'wpb_settings_page',
			'wpb_settings_section',
			array(
				'label_for'         => 'wpb_posts_per_page',
			)
		);
		add_settings_field(
			'wpb_order_dropdown_field',
			'Display Blog posts by order',
			array( $this,'wpb_settings_field_callback_function_2'),
			'wpb_settings_page',
			'wpb_settings_section',
			array(
				'label_for'         => 'wpb_selected_order',
			)
		);
		add_settings_field(
			'wpb_cat_dropdown_field',
			'Display Blogs on category selection.',
			array( $this,'wpb_settings_field_callback_function_3'),
			'wpb_settings_page',
			'wpb_settings_section',
			array(
				'label_for'         => 'wpb_selected_category',
			)
		);
	}
	
	function wpb_settings_field_callback_function_1($args) {
		$options = get_option( 'wpb_settings_options' );
		?>
		<input type="number" 
		id="<?php echo $args['label_for']; ?>" 
		name="wpb_settings_options[<?php echo $args['label_for']; ?>]"  
		placeholder="Enter numbers"
		value="<?php echo $options[$args['label_for']]; ?>">
		<?php					
	}
	function wpb_settings_field_callback_function_2($args) {
		$options = get_option( 'wpb_settings_options' );
		?>
		<select 
		name="wpb_settings_options[<?php echo $args['label_for']; ?>]"
		id="<?php echo $args['label_for']; ?>">
			<option value="rand">Select Order</option>
			<option <?php  echo ($options[$args['label_for']] == 'asc') ? 'selected' :''; ?> value="asc">Ascending Order</option>
			<option <?php  echo ($options[$args['label_for']] == 'desc') ? 'selected' :''; ?> value="desc">Descending Order</option>
		</select>
		<?php					
	}
	function wpb_settings_field_callback_function_3($args) {
		$options = get_option( 'wpb_settings_options' );
		?>
		<select 
		name="wpb_settings_options[<?php echo $args['label_for']; ?>]" 
		id="<?php echo $args['label_for']; ?>" >
			<option value="">Select Category</option>
			<?php
				// Get all the categories
				$categories = get_terms( 'category' );
				var_dump($categories);
				
				// Loop through all the returned terms
				foreach ( $categories as $category ):
				?>
			<option <?php  echo ($options[$args['label_for']] == $category->name) ?  'selected' :''; ?> value="<?php echo $category->name ?>"><?php echo $category->name ?></option>
			<?php
			// end the loop
			endforeach;	
			?>
		</select>
		<?php		
	}
		
}