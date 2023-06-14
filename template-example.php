<?php
/* Template Name: example Page */
get_header();
get_template_part( 'template-parts/menu', '1' );
?>

<div class="container py-5">
    <div class="row gap-1">
        <?php
        // Custom query arguments
        $args = array(
            'post_type'      => 'example_post', // Replace with the slug of your custom post type
            'post_status'    => 'publish',
            'posts_per_page' => 10, // Number of posts to display
        );

        // Custom query
        $custom_query = new WP_Query( $args );

        // Check if there are any posts

        if ( $custom_query->have_posts() ): ?>
            <div id="first-grid" class="d-grid grid-3-col gap-1">
                <?php while ( $custom_query->have_posts() ): $custom_query->the_post(); ?>
                    <div class="card bg-white border-r-1 p-1">
                        <div class="d-flex align-items-center gap-1">
                            <?php
                            $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                            $alt_text = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
                            ?>
                            <img class="hooman" loading="lazy" src="<?php echo $featured_img_url; ?>" alt="<?php echo $alt_text; ?>" />
                            <div>
                                <h2><?php the_title(); ?></h2>
                                <?php
                                $tags = get_the_terms(get_the_ID(), 'custom_post_tag'); // Replace 'custom_post_tag' with your custom taxonomy slug
                                if ($tags && !is_wp_error($tags)) {
                                    foreach ($tags as $tag) {
                                        echo '<a href="' . get_term_link($tag) . '">' . $tag->name . '</a>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="py-2"><?php the_content(); ?></div>
                    </div>
                <?php endwhile; ?>

                <?php wp_reset_postdata(); ?>

            </div>
        <?php endif; ?>
        <?php
        // Get all countries
        $countries = get_terms( array(
            'taxonomy' => 'custom_post_tag',
            'hide_empty' => false,
        ));

        if($countries) : ?>
            <div id="response"></div>
            <form id="filter">
            <?php foreach($countries as $country) : ?>
                <input type="checkbox" name="country[]" value="<?php echo $country->term_id; ?>" /> <?php echo $country->name; ?><br/>
            <?php endforeach; ?>
            <button type="submit">Filter</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<?php get_footer(); ?>