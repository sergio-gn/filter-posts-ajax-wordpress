<?php
/* Template Name: example Page */
get_header('example');

get_template_part( 'template-parts/menu', '1' );
?>
<body>
    <div class="container py-5">
        <div class="row gap-1">
            <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; // Get the current page number
            
            // Custom query arguments
            $args = array(
                'post_type'      => 'example_post', // Replace with the slug of your custom post type
                'post_status'    => 'publish',
                'posts_per_page' => 12, // Number of posts to display per page
                'paged'          => $paged, // Current page number
            );
            
            // Custom query
            $custom_query = new WP_Query( $args );
            
            // Check if there are any posts
            if ( $custom_query->have_posts() ): ?>
                <div class="flex-d-column flex-1">
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
                <?php
                // Pagination for posts
                $total_pages = $custom_query->max_num_pages;
                if ($total_pages > 1) {
                    $current_page = max(1, get_query_var('paged'));
                    echo '<div class="pagination pt-4 text-center">';
                    echo paginate_links(array(
                        'base'      => get_pagenum_link(1) . '%_%',
                        'format'    => 'page/%#%',
                        'current'   => $current_page,
                        'total'     => $total_pages,
                        'prev_text' => '&laquo; Previous',
                        'next_text' => 'Next &raquo;',
                    ));
                    echo '</div>';
                }
                endif; ?>
                </div>
    
            <?php
            /******************************************************* FLAGS ********************************************************/
            // Get all countries
            $countries = get_terms(array(
                'taxonomy'   => 'custom_post_tag',
                'hide_empty' => false,
            ));
        
            // Construct the path to the JSON file
            $jsonFile = 'https://example.com/wp-content/themes/example/assets/json/flags.json';
            
            // Load the JSON data from the file
            $jsonData = file_get_contents($jsonFile);
            
            // Decode the JSON data
            $data = json_decode($jsonData, true);
            
            /******************************************************* FLAGS ********************************************************/
            ?>
    
            <?php if ($countries) :?>
                <div id="response" class="d-contents"></div>
                <form id="filter">
                    <?php foreach ($countries as $country):
                        $countryName = $country->name;
                        $countryClass = '';
            
                        // Find the country in the JSON data and retrieve the code
                        foreach ($data['countries'] as $countryData) {
                            if ($countryData['name'] === $countryName) {
                                $countryClass = strtolower($countryData['code']);
                                break;
                            }
                        }
                    ?>
                        <div class="d-flex">
                            <input type="checkbox" name="country[]" value="<?php echo $country->term_id; ?>" />
                            <span class="flag-icon fi-<?php echo $countryClass; ?>"></span>
                            <p><?php echo $countryName; ?></p>
                        </div>
                        
                    <?php endforeach; ?>
                    
                    <button type="submit">Filter</button>
                </form>
            <?php endif; ?>
    
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        jQuery(function($) {
            $('#filter').on('submit', function(e) {
                e.preventDefault();
    
                var country = [];
                $('input[name="country[]"]:checked').each(function() {
                    country.push(this.value);
                });
    
                $.ajax({
                    url: '<?php echo admin_url("admin-ajax.php"); ?>',
                    type: 'POST',
                    data: {
                        action: 'filter_posts',
                        country: country
                    },
                    success: function(response) {
                        $('#first-grid').remove();
                        $('#response').html(response);
                        $('.pagination').remove(); // Remove pagination when filtering results
                    }
                });
            });
        });
    </script>
</body>
<?php get_footer(); ?>