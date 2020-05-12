<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package portshowlio20
 */

get_header();
?>

	<main id="primary" class="site-main">
  <?php
    $loop = new WP_Query( array( 'post_type' => 'projects') );
    if ( $loop->have_posts() ) :
        while ( $loop->have_posts() ) : $loop->the_post(); ?>

            <a href="<?php the_permalink(); ?>">
              <div class="image">
                  <?php if ( the_field("feature_image") ) { ?>
                      <div class="featured-image">
                          <pre><?php echo the_field("feature_image") ?></pre>
                      </div>
                  <?php } ?>
                  <?php if ( the_field("feature_video") ) { ?>
                      <div class="featured-video">
                          <pre><?php echo the_field("feature_video") ?></pre>
                      </div>
                  <?php } ?>
              </div>
              <div class="project-meta">
                      <h2><?php echo get_the_title(); ?></h2>
                      <ul>
                      <?php
                        $terms = get_the_terms($post->ID, 'category');
                        $categories = [];

                        if( $terms ) {
                            foreach ($terms as $category) {
                                $categories[] = $category->name;
                            }
                        }

                        $categories = implode(', ', $categories);
                        echo $categories
                      ?>
                      </ul>
              </div>
            </a>

        <?php endwhile;
    endif;
    wp_reset_postdata();
  ?>
	</main><!-- #main -->

<?php
get_footer();
