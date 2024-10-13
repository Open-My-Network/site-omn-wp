<div class="slideshow-container">
        <?php
        // Get the current user's ID
        $user_id = get_current_user_id();
        
        $serialized_grade = get_user_meta($user_id, 'grades', true);
        $user_grade = maybe_unserialize($serialized_grade);
        
        
        $grade_to_compare = is_array($user_grade) && isset($user_grade[0]) ? $user_grade[0] : $user_grade;
           $args = array(
            'post_type'      => 'leep',
            'posts_per_page' => 3,
            'meta_query'     => array(
                array(
                    'key'   => 'pinned_content',
                    'value' => 'yes',
                ),
                array(
                    'key'     => 'selected_grade', // The meta key for the grade
                    'value'   => $grade_to_compare, // User's unserialized grade
                    'compare' => '=', // Exact match
                ),
            ),
        );

        $loop = new WP_Query($args);
        $slide_index = 1;
        if ($loop->have_posts()) :
            while ($loop->have_posts()) : $loop->the_post();
                $image = get_the_post_thumbnail_url(get_the_ID(), 'full');
                $title = get_the_title();

                // Output carousel slide
                ?>
                <div class="mySlides fade">
                    <div class="numbertext"><?php echo $slide_index . ' / ' . $loop->post_count; ?></div>
                    <img src="<?php echo esc_url($image); ?>">
                    <div class="text">
                        <h1><?php echo esc_html($title); ?></h1>
                    </div>
                    <div class="dot-container">
                        <?php for ($i = 1; $i <= $loop->post_count; $i++) : ?>
                            <span class="dot" onclick="currentSlide(<?php echo $i; ?>)"></span>
                        <?php endfor; ?>
                    </div>
                </div>
                <?php
                $slide_index++;
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </div>