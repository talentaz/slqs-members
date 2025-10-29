<?php
function slqs_referee() {
    global $wpdb;
    if (isset($_GET['email'])) {
        
        $email = $_GET['email'];
        $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}users WHERE user_email = %s", $email));
        if ($user) {
            $user_id = $user->ID; // Get the user ID

            // Step 2: Check the status in the slqs_members table
            $member = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}slqs_members WHERE user_id = %d", $user_id));
            
            if ($member && $member->status === 'PENDING') {
                // Step 3: Update the status to REVIEW
                $updated = $wpdb->update(
                    "{$wpdb->prefix}slqs_members",
                    array('status' => 'REVIEW'), // Data to update
                    array('user_id' => $user_id) // Where clause
                );

                if ($updated !== false) {
                    echo "Status updated to REVIEW for Member Number: $user->user_login";
                } else {
                    echo "Failed to update status for user ID: $user->user_login";
                }
            } else {
                echo "No member found or status is not PENDING for Member Number: $user->user_login";
            }
        } else {
            echo "No user found with the email: $email";
        }
        
    } else {
        echo "can not get new user email";
    }
}
add_shortcode('slqs_confirm', 'slqs_referee');