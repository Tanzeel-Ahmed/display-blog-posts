<?php
/**
 * Template Name: My Custom Blog Page 
 */
get_header();

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
    
    ?>
        </div>
            <div class="wpb-load-more-btn">
                <button type="button" name="load-more-btn" data-totalpages="<?php echo $total_pages; ?>" class="load-more-btn">Load More Posts</button>
            </div>
    </div>    
        </div>    