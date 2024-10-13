<?php
// Add custom field to 'Add New User' form with dynamic school list and grades as checkboxes
function my_custom_user_registration_field() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'omn_schools';

    // Query to retrieve the list of schools from the `omn_schools` table
    $schools = $wpdb->get_results("SELECT id, sch_name FROM $table_name");

    ?>
    <h3><?php _e('User Information', 'my-textdomain'); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="school"><?php _e('School'); ?></label></th>
            <td>
                <select name="school" id="school">
                    <option value=""><?php _e('Select a school', 'my-textdomain'); ?></option>
                    <?php
                    if ($schools) {
                        foreach ($schools as $school) {
                            echo '<option value="' . esc_attr($school->id) . '">' . esc_html($school->sch_name) . '</option>';
                        }
                    } else {
                        echo '<option value="">' . __('No schools found', 'my-textdomain') . '</option>';
                    }
                    ?>
                </select><br />
                <span class="description"><?php _e('Please select your school.', 'my-textdomain'); ?></span>
            </td>
        </tr>
        <tr>
            <th><?php _e('Grade(s)', 'my-textdomain'); ?></th>
            <td>
                <?php
                $grades = [
                    'grade-6' => 'Grade 6',
                    'grade-7' => 'Grade 7',
                    'grade-8' => 'Grade 8',
                    'grade-9' => 'Grade 9',
                    'grade-10' => 'Grade 10',
                    'grade-11' => 'Grade 11',
                    'grade-12' => 'Grade 12'
                    // Add more grades as needed
                ];

                foreach ($grades as $value => $label) {
                    echo '<label><input type="checkbox" name="grade[]" value="' . esc_attr($value) . '"> ' . esc_html($label) . '</label><br />';
                }
                ?>
            </td>
        </tr>
    </table>
    <?php
}
add_action('user_new_form', 'my_custom_user_registration_field');

// Save the custom field values when a new user is created
function save_custom_user_registration_field($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'omn_schools';

    if (isset($_POST['school'])) {
        // Fetch the school name based on the selected school ID
        $school_id = sanitize_text_field($_POST['school']);
        $school_name = $wpdb->get_var($wpdb->prepare("SELECT sch_name FROM $table_name WHERE id = %d", $school_id));
        
        // Save the school name in user meta
        if ($school_name) {
            update_user_meta($user_id, 'school_name', sanitize_text_field($school_name));
        }
    }

    if (isset($_POST['grade']) && is_array($_POST['grade'])) {
        // Sanitize and serialize the grades array before saving
        $grades = array_map('sanitize_text_field', $_POST['grade']);
        update_user_meta($user_id, 'grades', serialize($grades));
    } else {
        update_user_meta($user_id, 'grades', serialize([]));
    }
}

add_action('user_register', 'save_custom_user_registration_field');

// Function to save the custom fields on user profile update
function update_custom_user_profile_fields($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'omn_schools';

    // Update the school name based on the selected school ID
    if (isset($_POST['school']) && !empty($_POST['school'])) {
        $school_id = sanitize_text_field($_POST['school']);
        $school_name = $wpdb->get_var($wpdb->prepare("SELECT sch_name FROM $table_name WHERE id = %d", $school_id));

        if ($school_name) {
            update_user_meta($user_id, 'school_name', sanitize_text_field($school_name));
        }
    }

    // Update grades - ensure it's an array, sanitize it, and serialize for storage
    if (isset($_POST['grades']) && is_array($_POST['grades'])) {
        $grades = array_map('sanitize_text_field', $_POST['grades']);
        update_user_meta($user_id, 'grades', serialize($grades));
    } else {
        update_user_meta($user_id, 'grades', serialize([]));
    }
}
add_action('edit_user_profile_update', 'update_custom_user_profile_fields');
add_action('personal_options_update', 'update_custom_user_profile_fields');

// Display the school and grades on user profile
function display_user_information_on_profile($user) {
    $school_name = get_user_meta($user->ID, 'school_name', true);
    
    // Use maybe_unserialize to ensure proper unserialization of the grades
    $grades = maybe_unserialize(get_user_meta($user->ID, 'grades', true));

    if (!is_array($grades)) {
        $grades = []; // Handle the case when grades are empty or not properly stored
    }

    $all_grades = [
        'grade-6' => 'Grade 6',
        'grade-9' => 'Grade 9',
        'grade-10' => 'Grade 10'
    ];

    ?>
    <h3><?php _e('User Information', 'my-textdomain'); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="school"><?php _e('School'); ?></label></th>
            <td>
                <?php
                if (current_user_can('manage_options')) {
                    ?>
                    <select name="school" id="school">
                        <option value=""><?php _e('Select a school', 'my-textdomain'); ?></option>
                        <?php
                        global $wpdb;
                        $table_name = $wpdb->prefix . 'omn_schools';
                        $schools = $wpdb->get_results("SELECT id, sch_name FROM $table_name");
                        foreach ($schools as $school) {
                            $selected = $school->id == $school_name ? ' selected="selected"' : '';
                            echo '<option value="' . esc_html($school->sch_name) . '"' . $selected . '>' . esc_html($school->sch_name) . '</option>';
                        }
                        ?>
                    </select>
                    <?php
                } else {
                    echo esc_html($school_name ? $school_name : 'No school selected');
                }
                ?>
            </td>
        </tr>
        <tr>
            <th><label for="grades"><?php _e('Grade(s)'); ?></label></th>
            <td>
                <?php
                foreach ($all_grades as $value => $label) {
                    $checked = in_array($value, $grades) ? ' checked="checked"' : '';
                    if (current_user_can('manage_options')) {
                        echo '<label><input type="checkbox" name="grades[]" value="' . esc_attr($value) . '"' . $checked . '> ' . esc_html($label) . '</label><br />';
                    } else {
                        echo '<label><input type="checkbox" name="grades[]" value="' . esc_attr($value) . '"' . $checked . ' disabled> ' . esc_html($label) . '</label><br />';
                    }
                }
                ?>
            </td>
        </tr>
    </table>
    <?php
}

// add_action('template_redirect', 'redirect_members_page');

// function redirect_members_page() {
//     // Check if the request is for a URL starting with '/members'
//     if (strpos($_SERVER['REQUEST_URI'], '/members') === 0) {
//         // Get the cookie value
//         if (isset($_COOKIE['jwt_token'])) {
//             $token = urlencode($_COOKIE['jwt_token']);
//             // Redirect to the new site with the token as a parameter
//             wp_redirect('https://account.openmynetwork.com?token=' . $token, 301);
//             exit;
//         } else {
//             // Redirect without the token if the cookie doesn't exist
//             wp_redirect('https://openmynetwork.com/login', 301);
//             exit;
//         }
//     }
// }



add_action('show_user_profile', 'display_user_information_on_profile');
add_action('edit_user_profile', 'display_user_information_on_profile');
?>
