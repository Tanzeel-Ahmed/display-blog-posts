<?php
/**
 * Template Name: My Custom Blog Page 
 */
?>   
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs</title>
</head>
<body>            
<?php
get_header();

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
        ) ;
    }

$query = new WP_Query($wpquery);
?>
    <div class="wpb-main-container">
        <div class="wpb-sub-container">
    <?php
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
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                </div>  
                <div class="wpb-posts-datetime">
                    <?php echo get_the_date(); ?>
                    <?php echo get_the_time(); ?>
                    <span class="wpb-posts-read"><a href="<?php the_permalink(); ?>">Read</a></span>
                </div>       
            </article>
<?php }
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
 <?php 
get_footer();
?>
</body>
</html> 