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

	public function blog_post_type() {
		$labels = array(
			'name'                  => _x( 'Blogs', 'Blogs', $this->plugin_name ),
			'singular_name'         => _x( 'Blog', 'Blog', $this->plugin_name ),
			'menu_name'             => _x( 'Blogs', 'Admin Menu text', $this->plugin_name ),
			'name_admin_bar'        => _x( 'Blog', 'Add New on Toolbar', $this->plugin_name ),
			'add_new'               => __( 'Add New', $this->plugin_name ),
			'add_new_item'          => __( 'Add New Blog', $this->plugin_name ),
			'new_item'              => __( 'New Blog', $this->plugin_name ),
			'edit_item'             => __( 'Edit Blog', $this->plugin_name ),
			'view_item'             => __( 'View Blog', $this->plugin_name ),
			'all_items'             => __( 'All Blogs', $this->plugin_name ),
			'search_items'          => __( 'Search Blogs', $this->plugin_name ),
			'featured_image'        => _x( 'Blog Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', $this->plugin_name ),
			'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', $this->plugin_name ),
			'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', $this->plugin_name ),
			'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', $this->plugin_name ),
			'archives'              => _x( 'Blog archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', $this->plugin_name ),
			
		);
		$supports = array(
			'title',
			'editor',
			'author',
			'thumbnail', 
			'excerpt', 
			'comments'
		);
		$args = array(
			'labels'             => $labels,
			'supports'           => $supports,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'blogs'),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'show_in_rest' 		 => true,
			'menu_icon'			 => 'dashicons-welcome-write-blog'
		);
	 
		register_post_type( 'blog', $args );
	}


}
