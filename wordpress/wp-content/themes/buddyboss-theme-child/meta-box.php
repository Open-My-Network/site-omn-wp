<?php

// Function to add the meta box to selected post types
function add_school_meta_box() {
    $post_types = array('survey', 'leep', 'teacher-time', 'student-workbook');

    foreach ($post_types as $post_type) {
        add_meta_box(
            'school_meta_box',       // Meta box ID
            'Select School, Grade, and Pinned', // Meta box title
            'display_school_meta_box', // Callback function
            $post_type,              // Apply to each post type
            'normal',                // Display below the editor
            'high'                   // Priority
        );
    }
}
add_action('add_meta_boxes', 'add_school_meta_box');

// Callback function to display the meta box form fields
function display_school_meta_box($post) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'omn_schools';  // Ensure correct table name with prefix

    // Fetch school records from the schools table
    $schools = $wpdb->get_results("SELECT id, sch_name FROM $table_name");

    // Start of the div wrapper to keep all dropdowns in the same line
    echo '<div style="display: flex; justify-content: space-between; align-items: center;">';

    // Display the dropdown for selecting a school
    echo '<div>';
    echo '<label for="school_dropdown">School: </label>';
    echo '<select name="school_dropdown" id="school_dropdown">';
    echo '<option value="">-- Select School --</option>';
    foreach ($schools as $school) {
        $selected = get_post_meta($post->ID, 'selected_school', true) == $school->id ? 'selected' : '';
        echo '<option value="' . esc_attr($school->id) . '" ' . $selected . '>' . esc_html($school->sch_name) . '</option>';
    }
    echo '</select>';
    echo '</div>';

    // Display the dropdown for selecting a grade (grade-1 to grade-9)
    echo '<div>';
    echo '<label for="grade_dropdown">Grade: </label>';
    echo '<select name="grade_dropdown" id="grade_dropdown">';
    echo '<option value="">-- Select Grade --</option>';
    for ($i = 1; $i <= 9; $i++) {
        $grade_value = 'grade-' . $i;
        $selected = get_post_meta($post->ID, 'selected_grade', true) == $grade_value ? 'selected' : '';
        echo '<option value="' . esc_attr($grade_value) . '" ' . $selected . '>Grade ' . esc_html($i) . '</option>';
    }
    echo '</select>';
    echo '</div>';

    // Display the dropdown for "Pinned" content (Yes/No)
    echo '<div>';
    echo '<label for="pinned_dropdown">Pinned: </label>';
    echo '<select name="pinned_dropdown" id="pinned_dropdown">';
    echo '<option value="">-- Select --</option>';
    $pinned_selected = get_post_meta($post->ID, 'pinned_content', true);
    echo '<option value="yes" ' . selected($pinned_selected, 'yes', false) . '>Yes</option>';
    echo '<option value="no" ' . selected($pinned_selected, 'no', false) . '>No</option>';
    echo '</select>';
    echo '</div>';

    // Close the div wrapper
    echo '</div>';
}

// Function to save the meta box data
function save_school_meta_data($post_id) {
    // Save selected school
    if (array_key_exists('school_dropdown', $_POST)) {
        update_post_meta(
            $post_id,
            'selected_school',
            sanitize_text_field($_POST['school_dropdown'])
        );
    }

    // Save selected grade in the format 'grade-X'
    if (array_key_exists('grade_dropdown', $_POST)) {
        update_post_meta(
            $post_id,
            'selected_grade',
            sanitize_text_field($_POST['grade_dropdown'])  // Now stores 'grade-X'
        );
    }

    // Save pinned content (Yes/No)
    if (array_key_exists('pinned_dropdown', $_POST)) {
        update_post_meta(
            $post_id,
            'pinned_content',
            sanitize_text_field($_POST['pinned_dropdown'])
        );
    }
}
add_action('save_post', 'save_school_meta_data');
