<?php

function slqs_enqueue_scripts_and_styles() {
    // Enqueue Bootstrap CSS
    wp_enqueue_style('bootstrap-style', 'https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css');

    // Enqueue jQuery (this should be loaded first)
    wp_enqueue_script('jquery');

    // Enqueue Bootstrap JS, dependent on jQuery
    wp_enqueue_script('bootstrap-script', 'https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js', array('jquery'), null, true);

    // Enqueue custom styles
    wp_enqueue_style('slqs-style', plugins_url('assets/style.css', __FILE__));
    wp_enqueue_style('icon-style', plugins_url('assets/icon/icons.css', __FILE__));
    wp_enqueue_style('toastr-style', plugins_url('assets/toastr.min.css', __FILE__));
    
    wp_enqueue_script('toastr-script', plugins_url('assets/toastr.min.js', __FILE__), array('jquery'), null, true);

    // Enqueue custom script, dependent on jQuery
    wp_enqueue_script('slqs-script', plugins_url('assets/script.js', __FILE__), array('jquery'), null, true);
    wp_localize_script('slqs-script', 'myAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));

    // Enqueue parsley script, dependent on jQuery
    wp_enqueue_script('parsley-script', plugins_url('assets/parsley.min.js', __FILE__), array('jquery'), true);

    // Enqueue input mask script, dependent on jQuery
    wp_enqueue_script('mask-script', plugins_url('assets/jquery.inputmask.bundle.js', __FILE__), array('jquery'), true);
}

function slqs_get_emirates_dropdown($name, $editName = '') {
    global $wpdb;

    // Define the table name
    $table_name = $wpdb->prefix . 'slqs_location';
    $locations = $wpdb->get_results("SELECT id, location FROM $table_name");
    $output = '<select class="form-control regular-text" ' .'name='.$name.'>';
    if ($locations) {
        foreach ($locations as $location) {
            $selected = ($editName == $location->id) ? ' selected' : '';
            $output .= '<option value="' . esc_attr($location->id) . '"' . $selected . '>' . esc_html($location->location) . '</option>';
        }
    } else {
        // Optionally handle the case where no locations are found
        $output .= '<option value="">No locations available</option>';
    }

    // Close the select element
    $output .= '</select>';

    return $output;
}

function slqs_get_member_group($name, $selected_ids = []) {
    global $wpdb;

    // Define the table name
    $table_name = $wpdb->prefix . 'slqs_member_type';
    $type_names = $wpdb->get_results("SELECT id, type_name FROM $table_name");
    
    // Create the select element with the multiple attribute
    $output = '<select class="form-control regular-text" name="' . esc_attr($name) . '[]" multiple>';

    if ($type_names) {
        foreach ($type_names as $type_name) {
            // Check if the current type_id is in the selected_ids array
            $selected = in_array($type_name->id, $selected_ids) ? ' selected' : '';
            $output .= '<option value="' . esc_attr($type_name->id) . '"' . $selected . '>' . esc_html($type_name->type_name) . '</option>';
        }
    } else {
        // Optionally handle the case where no group types are found
        $output .= '<option value="">No group type available</option>';
    }

    // Close the select element
    $output .= '</select>';

    return $output;
}


function get_member_by_id($member_id) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'slqs_members'; // Replace with your actual table name

    $member = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT 
                m.*, 
                u.user_login, 
                u.user_email,
                GROUP_CONCAT(t.type_name) AS type_names,
                GROUP_CONCAT(g.member_type_id) AS member_type_ids
            FROM 
                {$table_name} AS m
            LEFT JOIN 
                {$wpdb->prefix}users AS u ON m.user_id = u.ID
            LEFT JOIN 
                {$wpdb->prefix}slqs_member_group AS g ON m.id = g.member_id
            LEFT JOIN 
                {$wpdb->prefix}slqs_member_type AS t ON g.member_type_id = t.id
            WHERE 
                m.id = %d
            GROUP BY 
                m.id",
            $member_id
        )
    );

    if ($member) {
        $member->member_type_ids = !empty($member->member_type_ids) ? explode(',', $member->member_type_ids) : [];
    }

    return $member;
}


add_action('admin_post_update_member', 'handle_update_member');

function handle_update_member() {
    // Check if the user has the right capability
    if (!current_user_can('edit_users')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }

    // Sanitize and validate input data
    $member_id = intval($_POST['member_id']);
    global $wpdb;
    // member data
    $email = sanitize_email($_POST['email']);
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $date_of_birth = sanitize_text_field($_POST['date_of_birth']);
    $emirate_of_location = sanitize_text_field($_POST['emirate_of_location']);
    $current_address = sanitize_text_field($_POST['current_address']);
    $mobile_primary = sanitize_text_field($_POST['mobile_primary']);
    $mobile_alternative = sanitize_text_field($_POST['mobile_alternative']);
    $email_alternative = sanitize_email($_POST['email_alternative']);
    $joining_date = sanitize_text_field($_POST['joining_date']);

    $current_employer = sanitize_text_field($_POST['current_employer']);
    $employer_address = sanitize_text_field($_POST['employer_address']);
    $current_work_location = sanitize_text_field($_POST['current_work_location']);
    $company_phone = sanitize_text_field($_POST['company_phone']);
    $company_email = sanitize_email($_POST['company_email']);
    $current_position = sanitize_text_field($_POST['current_position']);

    $emergency_contact_name = sanitize_text_field($_POST['emergency_contact_name']);
    $emergency_contact_relationship = sanitize_text_field($_POST['emergency_contact_relationship']);
    $emergency_contact_address = sanitize_text_field($_POST['emergency_contact_address']);
    $emergency_contact_mobile = sanitize_text_field($_POST['emergency_contact_mobile']);
    $emergency_contact_email = sanitize_email($_POST['emergency_contact_email']);

    $referee_name = sanitize_text_field($_POST['referee_name']);
    $referee_membership_no = sanitize_text_field($_POST['referee_membership_no']);
    $referee_mobile = sanitize_text_field($_POST['referee_mobile']);

    // formatted phone number
    $mobile_primary = formatted_mobile($mobile_primary);
    $mobile_alternative = formatted_mobile($mobile_alternative);
    $company_phone = formatted_mobile($company_phone);
    $emergency_contact_mobile = formatted_mobile($emergency_contact_mobile);
    $referee_mobile = formatted_mobile($referee_mobile);

    $member_type_ids = isset($_POST['member_type_id']) ? array_map('intval', $_POST['member_type_id']) : []; // Array of selected member type IDs

    // Update the member data in the slqs_members table
    $updated = $wpdb->update(
        "{$wpdb->prefix}slqs_members",
        array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'date_of_birth' => $date_of_birth,

            'emirate_of_location' => $emirate_of_location,
            'current_address' => $current_address,
            'mobile_primary' => $mobile_primary,
            'mobile_alternative' => $mobile_alternative,
            'email_alternative' => $email_alternative,
            'joining_date' => $joining_date,

            'current_employer' => $current_employer,
            'employer_address' => $employer_address,
            'current_work_location' => $current_work_location,
            'company_phone' => $company_phone,
            'company_email' => $company_email,
            'current_position' => $current_position,

            'emergency_contact_name' => $emergency_contact_name,
            'emergency_contact_relationship' => $emergency_contact_relationship,
            'emergency_contact_address' => $emergency_contact_address,
            'emergency_contact_mobile' => $emergency_contact_mobile,
            'emergency_contact_email' => $emergency_contact_email,

            'referee_name' => $referee_name,
            'referee_membership_no' => $referee_membership_no,
            'referee_mobile' => $referee_mobile

        ),
        array('id' => $member_id), // Where clause
        array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'), // Data format
        array('%d') // Where format
    );

    $existing_member_types = $wpdb->get_col($wpdb->prepare("SELECT member_type_id FROM {$wpdb->prefix}slqs_member_group WHERE member_id = %d", $member_id));
    
     // Determine which member types to add and which to remove
     $types_to_add = array_diff($member_type_ids, $existing_member_types);
     $types_to_remove = array_diff($existing_member_types, $member_type_ids);
    // Remove old member types
    foreach ($types_to_remove as $type_id) {
        $wpdb->delete(
            "{$wpdb->prefix}slqs_member_group",
            array(
                'member_id' => $member_id,
                'member_type_id' => $type_id,
            ),
            array('%d', '%d') // Where format
        );
    }
     
     // Add new member types
     foreach ($types_to_add as $type_id) {
         $wpdb->insert(
             "{$wpdb->prefix}slqs_member_group",
             array(
                 'member_id' => $member_id,
                 'member_type_id' => $type_id,
             ),
             array('%d', '%d') // Data format
         );
     }

    if ($updated !== false) {
        // Log or display the last query for debugging
        error_log($wpdb->last_query); // This will log the query to the PHP error log
        // Optionally, you can display it on the screen (not recommended for production)
        // echo '<pre>' . esc_html($wpdb->last_query) . '</pre>';

        wp_redirect(admin_url('admin.php?page=slqs-edit-member&member_id='.$member_id)); // Change 'your_page_slug' to your actual page slug
        exit;
    } else {
        wp_die('Failed to update member data. Last query: ' . esc_html($wpdb->last_query));
    }
}