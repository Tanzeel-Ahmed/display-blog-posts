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
	function my_template_array() {
		$temps = [];
		$temps['archive-blog-posts.php'] = 'Blog Post Template';
		return $temps;
	}
	function wpb_register_page_template( $page_templates, $theme, $post ){
		
		$templates = $this->my_template_array();

		foreach ( $templates as $tk=>$tv ){

			$page_templates[$tk] = $tv;
		}

		return $page_templates;
	}
	function my_template_select($template){

		global $post, $wpquery, $wpdb;

		$page_temp_slug = get_page_template_slug($post->ID);

		$templates = $this->my_template_array();

		if(isset ($templates[$page_temp_slug])) {

			$template = plugin_dir_path(__FILE__).'templates/'.$page_temp_slug;

		}
		return $template;
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


	function shortcode_for_blogs_page($atts) {

		extract(shortcode_atts(array(
			'post' => -1,
			'order' => 'ASC',
			'category' => '',
		 ), $atts));

		 

	ob_start();


// Recently added post. Display on the top
?>

<div class="wpb-recent-post-main-container">
<?php
    $recent_posts = wp_get_recent_posts(array(
        'post_type'   =>'post', 
        'numberposts' => 1, // Number of recent posts thumbnails to display
        'post_status' => 'publish' 
    ));
    foreach($recent_posts as $post) : ?>
    <article class="wpb-recent-posts-container">   
        <div class="inner-container">
            <div class="wpb-recent-posts-featured-img">
                <img class="recent-featured-img" src="<?php echo get_the_post_thumbnail_url($post['ID']) ?>" >
            </div>
            <div class="wpb-recent-posts-content-container">
                <div class="wpb-recent-posts-category-frame">
                    <div class="wpb-recent-posts-category">
                        <?php
                        foreach((get_the_category($post['ID'])) as $category) { ?>
                            <a href="<?php echo get_the_permalink($post['ID']); ?>"><?php echo $category->cat_name  ?></a>
                        <?php
                        }
                        ?>
                    </div>
                </div> 
                    <div class="wpb-recent-posts-title">
                        <h2>
                            <a href="<?php echo get_the_permalink($post['ID']); ?>"><?php echo get_the_title($post['ID']); ?> </a>
                        </h2>
                    </div>  
                    <div class="wpb-recent-posts-content">
                        <p>
                        <?php echo wp_trim_words( $post['post_content'], 20, '...' ); ?>
                        </p>
                    </div>
                    <div class="wpb-recent-posts-datetime">
                        <?php 
                        $date = $post['post_date'];
                        echo date('F d, Y', strtotime($date));
                        ?>
                        <div class="read-more">
                            <?php 
                            $date = $post['post_date'];
                            echo date(' h:i A', strtotime($date));
                            ?>
                            <span class="wpb-recent-posts-read"><a href="<?php echo get_the_permalink($post['ID']); ?>">Read</a></span>
                        </div>
                    </div>  
            </div>
        </div>         
    </article>
    <?php endforeach; wp_reset_query(); ?>
</div>
<!-- Display categories nav tab list-->
<div class="wpb-main-container">
    <?php
    echo '<ul class="nav-tabs" role="tablist">';
        $args = array(
            'taxonomy'    => 'category',
            'hide_empty'  => 0,
            'orderby'     => 'name',
            'order'       => 'ASC',
            
        );
        echo '<li class="all-nav-tabs-list"> All
             </li>';
        $categories = get_categories($args);
        foreach($categories as $category) { 
                 
          echo '<li class="single-nav-tabs-list">
                    <span data-href="'.$category->slug.'" role="tab" data-toggle="tab">    
                    '.$category->name.'
                    </span>
                </li>';
        }
    echo '</ul>';
    // Display posts on category base
        echo '<div class="investors-content">';
        foreach($categories as $category) { 
            echo '<div class="tab-pane" id="' . $category->slug.'">';
            $catslug = $category->slug;
            $the_query = new WP_Query(array(
                'post_type' => 'post',
                'posts_per_page' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field'    => 'slug',
                        'terms'    => array( $catslug ),
                        'operator' => 'IN'
                    ),
                ),
            ));
        while ( $the_query->have_posts() ) : 
            $the_query->the_post();?>
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
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?> </a>
                </h2>
            </div>  
            <div class="wpb-posts-datetime">
                <?php echo get_the_date(); ?>
                <?php echo get_the_time(); ?>
                <span class="wpb-posts-read"><a href="<?php the_permalink(); ?>">Read</a></span>
            </div>       
        </article>
        <?php endwhile;
            wp_reset_postdata();
            echo '</div>';
        } 
        echo '</div>';
        ?>
    <div class="wpb-all-content-container"> 
        <div class="wpb-sub-container">
        <?php
        $options = get_option('wpb_settings_options');
		
		if(empty($atts) ){
			$wpquery = array(
				
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' =>  $options['wpb_posts_per_page'],
				'orderby'        =>  'title',
				'order'          =>  $options['wpb_selected_order'],
				'paged'          => 1,
			);
			
			if(!empty($options['wpb_selected_category'])){
				$wpquery['tax_query'] =array(
					array(
					'taxonomy'   => 'category',
					'field'      => 'slug',
					'terms'      => $options['wpb_selected_category']
					)
				);
        }
		}else{
			
		 //var_dump($atts['category']);

			$wpquery = array(
             
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' =>  $atts['post'],
				'orderby'        =>  'title',
				'order'          =>  $atts['order'],
				'paged'          => 1,
			);
			
			if(!empty($atts['category'])){
				 $wpquery['tax_query'] =array(
					array(
					'taxonomy'   => 'category',
					'field'      => 'slug',
					'terms'      => $atts['category']
					)
				);
			}

		}
     
        $query = new WP_Query($wpquery);
            while($query->have_posts()) {
            $query->the_post();
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
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?> </a>
                        </h2>
                    </div>  
                    <div class="wpb-posts-datetime">
                        <?php echo get_the_date(); ?>
                        <?php echo get_the_time(); ?>
                        <span class="wpb-posts-read"><a href="<?php the_permalink(); ?>">Read</a></span>
                    </div>       
                </article>
    <?php   }
        wp_reset_postdata();
        $query = array(
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        );
        $all_posts   = new WP_Query($query);
        $total_posts = $all_posts->found_posts;
        $total_pages = $total_posts / $options['wpb_posts_per_page'];
        $total_pages = ceil($total_pages);
		if($options['wpb_posts_per_page'] == '-1'){
			$total_pages = '1';
		}
    
    ?>
        </div>
            <div class="wpb-load-more-btn">
                <button type="button" name="load-more-btn" data-totalpages="<?php echo $total_pages; ?>" class="load-more-btn">Load More Posts</button>
            </div>
    </div>    
        </div>    
<?php
        return ob_get_clean();
	
	}



}
