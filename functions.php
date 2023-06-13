<?php
add_action( 'wp_ajax_filter_posts', 'filter_posts_ajax' );
add_action( 'wp_ajax_nopriv_filter_posts', 'filter_posts_ajax' );

function filter_posts_ajax() {
    $country_ids = $_POST['country'];
    $args = array(
        'post_type' => 'hoomans_post',
        'post_status' => 'publish',
        'posts_per_page' => 10,
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

    if ( $custom_query->have_posts() ): ?>
        <div class="d-grid grid-3-col">
            <?php while ( $custom_query->have_posts() ):
                $custom_query->the_post();

                ?>
                    <div class="card">
                        <img loading="lazy" src="<?php echo $card['img'] ?>" alt="<?php echo $card['alt'] ?> "/>
                        <h2><?php the_title(); ?></h2>
                        <?php
                            $tags = get_the_terms( get_the_ID(), 'custom_post_tag' ); // Replace 'custom_post_tag' with your custom taxonomy slug
                            if ( $tags && ! is_wp_error( $tags ) ) {
                                foreach ( $tags as $tag ) {
                                    echo '<a href="' . get_term_link( $tag ) . '">' . $tag->name . '</a>';
                                }
                            }
                        ?>

                        <p><?php the_content(); ?></p>
                        <p><?php echo $card['content'] ?></p>
                    </div>
            <?php endwhile;
                wp_reset_postdata();
            ?>
        </div>
    <?php endif;

    $response = ob_get_clean();
    echo $response;

    wp_die(); // Important: to avoid "0" being appended to the response
}
