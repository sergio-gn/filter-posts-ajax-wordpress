<?php

add_action( 'wp_ajax_filter_posts', 'filter_posts_ajax' );
add_action( 'wp_ajax_nopriv_filter_posts', 'filter_posts_ajax' );

function filter_posts_ajax() {
    $country_ids = $_POST['country'];
    $args = array(
        'post_type' => 'example_post',
        'post_status' => 'publish',
        'posts_per_page' => 50,
        'tax_query' => array(
            array(
                'taxonomy' => 'custom_post_tag',
                'field' => 'term_id',
                'terms' => $country_ids
            )
        )
    );

    $custom_query = new WP_Query( $args );

    ob_start();

    // Check if there are any posts
        if ( $custom_query->have_posts() ): ?>
            <div id="first-grid" class="d-grid grid-3-col gap-3-1 flex-1">
                <?php while ( $custom_query->have_posts() ): $custom_query->the_post(); ?>
                    <div class="card bg-white border-r-1 p-1">
                        <div class="d-flex align-items-center gap-1">
                            <?php
                            $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                            $alt_text = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
                            ?>
                            <img class="example" loading="lazy" src="<?php echo $featured_img_url; ?>" alt="<?php echo $alt_text; ?>"/>
                            <div class="max-width-min">
                                <h2><?php the_title();?></h2>
                                <p>
                                    <?php
                                    $tags = get_the_terms(get_the_ID(), 'custom_post_tag');
                                    if ($tags && !is_wp_error($tags)) {
                                        foreach ($tags as $tag) {
                                            echo  $tag->name ;
                                        }
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                        <div class="py-2"><?php the_content(); ?></div>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        <?php endif; 

    $response = ob_get_clean();
    echo $response;

    wp_die(); // Important: to avoid "0" being appended to the response
}