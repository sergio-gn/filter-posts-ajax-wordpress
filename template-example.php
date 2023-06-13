<div class="container">
    <div class="row">
        <?php
        // Custom query arguments
        $args = array(
            'post_type'      => 'post_custom', // Replace with the slug of your custom post type
            'post_status'    => 'publish',
            'posts_per_page' => 10, // Number of posts to display
        );

        // Custom query
        $custom_query = new WP_Query( $args );

        // Check if there are any posts

        if ( $custom_query->have_posts() ): ?>
            <div class="d-grid grid-3-col">
                <?php while ( $custom_query->have_posts() ):
                    $custom_query->the_post();

                    // Display post content
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
                <?php
                    endwhile;
                    // Reset post data
                    wp_reset_postdata();
                ?>
            </div>
        <?php endif; ?>
        <?php
        // Get all countries
        $countries = get_terms( array(
            'taxonomy' => 'custom_post_tag',
            'hide_empty' => false,
        ));

        if($countries) : ?>
            <form id="filter">
            <?php foreach($countries as $country) : ?>
                <input type="checkbox" name="country[]" value="<?php echo $country->term_id; ?>" /> <?php echo $country->name; ?><br/>
            <?php endforeach; ?>
            <button type="submit">Filter</button>
            </form>

            <div id="response"></div>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="./script.js"></script>