<?php

/**
 * Handle filter AJAX request
 */

add_action('wp_enqueue_scripts', 'add_filter_script');
function add_filter_script()
{
  wp_enqueue_script(
    'filter', // name your script so that you can attach other scripts and de-register, etc.
    get_template_directory_uri() . '/js/filter.js', // this is the location of your script file
    ['jquery'], // this array lists the scripts upon which your script depends
    null,
    true
  );

  wp_localize_script('filter', 'wp_ajax', [
    'ajax_url' => admin_url('admin-ajax.php'),
    'security' => wp_create_nonce('wp-ajax-nonce'),
  ]);
}

add_action('wp_ajax_nopriv_filter', 'filter_ajax');
add_action('wp_ajax_filter', 'filter_ajax');

function filter_ajax()
{
  // 1. first things first, if you're not legit, we bail
  if (!check_ajax_referer('wp-ajax-nonce', 'security', false)) {
    wp_send_json_error('Invalid security token sent.');
    wp_die();
  }

  // 2. collect & data
  $toggle = $_POST['toggle'];
  $works_filters = $_POST['worksFilters'];
  $students_filters = $_POST['studentsFilters'];

  // 3. prepare args for queries (works filters, students filters)
  // 3a. works filters
  $works_args = [
    'post_type' => 'projects',
  ];

  if (isset($works_filters)) {
    // convert array of strings to an array of integers
    $works_filters = array_map('intval', $works_filters);
    $works_args['category__in'] = $works_filters;
  }

  // 3b. students filters

  if (isset($students_filters)) {
    $meta_query_array = ['relation' => 'OR'];

    foreach ($students_filters as $filter) {
      // 1. make a temp array with first item of: 'key' => 'focus'
      $temp_key = ['key' => 'focus'];
      // 2. add second item of temp array: 'value' => $item in students_filters,
      $temp_value = ['value' => $filter];
      $temp_compare = ['compare' => 'LIKE'];
      // array_push($temp_array, $temp_item);
      $temp_merge = $temp_key + $temp_value + $temp_compare;
      // 3. push this temp array to $meta_query_array
      array_push($meta_query_array, $temp_merge);
    }

    $students_args = [
      'meta_query' => $meta_query_array,
    ];
  }

  // 4. create conditional queries based on:
  //        a. toggle state
  //        b. works filters
  //        c. students filters

  if (
    ($works_filters == null && $toggle == "works") ||
    ($students_filters == null && $toggle == "students")
  ) {
    echo '<pre>';
    echo 'For best results, please select at least one filter. 🙄';
    echo '</pre>';
  } elseif ($toggle == "works") {
    $works_query = new WP_Query($works_args);
    if ($works_query->have_posts()):
      while ($works_query->have_posts()):
        $works_query->the_post(); ?>

            <a href="<?php the_permalink(); ?>">
              <div class="image">
                <div class="featured-image">
                  <img class="my_class" <?php responsive_image(
                    get_field('featured_image'),
                    'thumb-640',
                    '640px'
                  ); ?>  alt="text" />
                </div>
              </div>
              <div class="project-meta">
                <h2><?php echo get_the_title(); ?></h2>
                <ul><?php
                $terms = get_the_terms($post->ID, 'category');
                $categories = [];
                if ($terms) {
                  foreach ($terms as $category) {
                    $categories[] = $category->name;
                  }
                }

                if ($categories) {
                  foreach ($categories as $category) {
                    echo '<li>' . $category . '</li>';
                  }
                }
                ?></ul>
              </div>
            </a>

        <?php
      endwhile;
    endif;
    wp_reset_postdata();
  } elseif ($toggle == "students") {
    $students_query = new WP_User_Query($students_args);

    if (!empty($students_query->results)) {
      foreach ($students_query->results as $student) {

        $student_id = $student->ID;
        $link = esc_url(get_author_posts_url($student_id));
        $aof_list = get_field('focus', 'user_' . $student_id);
        ?>

        <div>

          <pre>
          <?php var_dump($aof_list); ?>

          </pre>

          <a href="<?php echo $link; ?>">
          <?php echo $student->display_name; ?>
          </a>
          <ul>
            <?php foreach ($aof_list as $aof) {
              echo '<li>' . $aof . '</li>';
            } ?>
          </ul>
        </div>
     <?php
      }
    } else {
      echo 'No students found.';
    }
    // aaaaaand reset ...
    wp_reset_query();
  }

  die();
}
