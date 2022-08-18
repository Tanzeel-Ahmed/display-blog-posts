<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://blogs.local/
 * @since      1.0.0
 *
 * @package    Blog_Posts
 * @subpackage Blog_Posts/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Blog_Posts
 * @subpackage Blog_Posts/public
 * @author     Tanzeel Ahmed <tanzeel@wpminds.com>
 */
class Blog_Posts_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/blog-posts-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/blog-posts-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'jquery_object',array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),));

	}

	function wpb_page_template( $page_template ){
		if ( is_page( 'blogs' ) ) {
			$page_template = plugin_dir_path( __FILE__ ) . 'templates/archive-blog-posts.php';
		}
		return $page_template;
	}


	function wpb_load_more_posts(){
		$options = get_option('wpb_settings_options');
		$ajaxposts = new WP_Query([
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' =>  $options['wpb_posts_per_page'],
			'orderby'        =>  'title',
			'order'          =>  $options['wpb_selected_order'],
			'paged' 		 => $_POST['paged'],
			
		  ]);
		$post_meta_array = array();
		$max_pages = $ajaxposts->max_num_pages;
		while($ajaxposts->have_posts()) {
			$ajaxposts->the_post(); 
			ob_start();
			?> 
				<article class="wpb-posts-container">
				<div class="wpb-posts-category-frame">
						<div class="wpb-posts-category">
						<?php
						foreach((get_the_category()) as $category) { ?>
							<a href="<?php the_permalink(); ?>"><?php echo $category->cat_name  ?></a>
							<?php
						}
						?>
						</div>
					</div>    
					<div class="wpb-posts-featured-img">
						<img class="featured-img" src="<?php echo get_the_post_thumbnail_url() ?>" >
					</div>
					<div class="wpb-posts-title">
						<h2>
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>
					</div>  
					<div class="wpb-posts-datetime">
						<?php echo get_the_date(); ?>
						<?php echo get_the_time(); ?>
						<span class="wpb-posts-read"><a href="<?php the_permalink(); ?>">Read</a></span>
					</div>       
				</article>
		
			<?php

			$html = ob_get_clean();
			array_push($post_meta_array,$html);
		}
		$output = ob_get_contents();
		wp_send_json($post_meta_array);
	}


}
