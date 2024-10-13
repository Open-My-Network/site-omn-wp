<?php
/*
Template Name: Leep Courses
*/

get_header();

if ( ! is_user_logged_in() ) {
    wp_redirect( wp_login_url() );
    exit;
}

// Get the current user's ID
$user_id = get_current_user_id();

$serialized_grade = get_user_meta($user_id, 'grades', true);
$user_grade = maybe_unserialize($serialized_grade);


$grade_to_compare = is_array($user_grade) && isset($user_grade[0]) ? $user_grade[0] : $user_grade;
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
/* Original CSS for Carousel */
* {box-sizing: border-box}
body {font-family: Verdana, sans-serif; margin:0}
.mySlides {display: none; position: relative;}
img {vertical-align: middle;}

/* Slideshow container */
.slideshow-container {
  max-width: 100%;
  position: relative;
  margin: auto;
  overflow: hidden; /* Ensure content doesn't overflow with curved border */
  border-radius: 20px; /* Apply curved border */
}

/* Caption text */
.text {
  color: #f2f2f2;
  font-size: 24px; /* Increased font size */
  font-weight: bold; /* Increased font weight */
  padding: 8px 12px;
  position: absolute;
  top: 50%;
  left: 5%; /* Align text to the left */
  transform: translateY(-50%);
  width: 40%;
  text-align: left; /* Align text to the left */
}

.text h1 {
  font-size: 30px;
  font-weight: bold;
  margin: 0;
}

/* Number text (1/3 etc) */
.numbertext {
  color: #f2f2f2;
  font-size: 12px;
  padding: 8px 12px;
  position: absolute;
  top: 0;
}

/* The dots/bullets/indicators */
.dot-container {
  position: absolute;
  bottom: 20px;
  width: 100%;
  text-align: center;
}

.dot {
  cursor: pointer;
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbb;
  border-radius: 50%;
  display: inline-block;
  transition: background-color 0.6s ease;
}

.active, .dot:hover {
  background-color: #717171;
}

/* Fading animation */
.fade {
  animation-name: fade;
  animation-duration: 1.5s;
}

@keyframes fade {
  from {opacity: .4} 
  to {opacity: 1}
}

/* On smaller screens, decrease text size */
@media only screen and (max-width: 300px) {
  .prev, .next,.text {font-size: 11px}
}

/* Adjust image height to 75vh */
.mySlides img {
  width: 100%;
  height: 75vh;
  object-fit: cover;
}

/* Hide next & previous buttons */
.prev, .next {
  display: none;
}

/* Styles for horizontal post list */
.post-list-container {
  padding: 20px;
  margin: 20px 0; /* Add margin to separate from the carousel */
}

h2.chapter-title {
  padding-left: 20px;
  margin-top: 40px;
  font-size: 24px;
  font-weight: bold;
}

.post-list {
  display: flex;
  overflow-x: auto;
  gap: 20px; /* Space between posts */
}

.post-item {
  flex: 0 0 auto; /* Prevent shrinking */
  width: 300px;
  background: #ffffff; /* White background */
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Drop shadow */
  overflow: hidden; /* Ensure the image fits well */
}

.post-item img {
  width: 100%;
  height: auto;
  border-radius: 10px 10px 0 0;
}

.post-item h3 {
  font-size: 20px;
  margin: 10px 0;
  padding: 6px;
  text-align: center; /* Center align title */
}

/* Hide scrollbar */
.post-list::-webkit-scrollbar {
  display: none;
}

.post-list {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}
</style>
</head>
<body>
<div id="primary" class="content-area">
    <main id="main" class="site-main">

    <!-- Carousel Code -->
    <?php include('include/carousel.php'); ?>
    
    <?php

    $chapters = get_terms(array(
        'taxonomy' => 'chapter',
        'hide_empty' => true,
    ));

    // Loop through each chapter
    foreach ($chapters as $chapter) :
        // Custom query to fetch Leep posts for the current chapter and the user's grade
        $post_args = array(
            'post_type' => 'leep',
            'posts_per_page' => 10,
            'orderby' => 'date', // Order by date
            'order' => 'ASC', // Ascending order
            'tax_query' => array(
                array(
                    'taxonomy' => 'chapter',
                    'field'    => 'slug',
                    'terms'    => $chapter->slug,
                ),
            ),
            'meta_query' => array(
                array(
                    'key'     => 'selected_grade', // Meta key for grade
                    'value'   => $grade_to_compare, // User's unserialized grade
                    'compare' => '=', // Exact match
                ),
            ),
        );
        $post_query = new WP_Query($post_args);
        ?>
        <!-- Display chapter title if posts are found -->
        <?php if ($post_query->have_posts()) : ?>
            <h2 class="chapter-title"><?php echo esc_html($chapter->name); ?></h2>
            <div class="post-list-container">
                <div class="post-list">
                    <?php while ($post_query->have_posts()) : $post_query->the_post(); ?>
                        <div class="post-item">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            <?php endif; ?>
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif;
        wp_reset_postdata();
    endforeach;
    ?>

    </main>
</div>
<script>
let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}
</script>

</body>
</html>

<?php
get_footer();
?>
