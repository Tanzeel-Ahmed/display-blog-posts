<?php
/**
 * Template Name: My Custom Blog Page 
 */
get_header();

    $options = get_option('wpb_settings_options');

    $wp_query = array(
        
        'post_type'      => 'post',
        'posts_per_page' =>  $options['wpb_posts_per_page'],
        'orderby'        =>  'title',
        'order'          =>   $options['wpb_selected_order']

    );
    if(!empty($options['wpb_selected_category'])){
        $wp_query['tax_query'] =array(
            array(
            'taxonomy'   => 'category',
            'field'      => 'slug',
            'terms'      => $options['wpb_selected_category']
            )
        ) ;
    }
    $query = new WP_Query($wp_query);
    while($query->have_posts()) {
        $query->the_post(); 
        ?> 
        <center> <h2><?php the_title(); ?><p><?php the_content(); ?></p></h2> </center>
        <?php
    }
  
get_footer();
?>