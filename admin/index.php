<?php
// Enqueue DataTables scripts and styles
function slqs_enqueue_datatables() {
    wp_enqueue_style('datatables-style', 'https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css');
    wp_enqueue_script('jquery');
    wp_enqueue_script('datatables-script', 'https://cdn.datatables.net/2.1.8/js/dataTables.min.js', array('jquery'), null, true);
    wp_enqueue_script('slqs-datatables-init', plugin_dir_url(__FILE__) . 'assets/datatables-init.js', array('datatables-script'), null, true);
    wp_enqueue_style('slqs-admin-style', plugin_dir_url(__FILE__) . 'assets/style.css');
}
add_action('admin_enqueue_scripts', 'slqs_enqueue_datatables');

// Admin: Admin dashboard menu
function slqs_admin_menu() {
    add_menu_page('SLQS Members', 'SLQS Members', 'manage_options', 'slqs-members', 'slqs_members_page');
}
add_action('admin_menu', 'slqs_admin_menu');

// Admin: Members page
function slqs_members_page() {
    echo '<div class="member-list">';
    echo '<h1>SLQS Members</h1>';
    echo '<table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Full Name</th>
                    <th>Member Number</th>
                    <th>Email</th>
                    <th>Type</th> 
                    <th>Status</th> 
                    <th>Action</th> 
                </tr>
            </thead>
        </table>';
    echo '</div>';
}

// AJAX handler to fetch members
function slqs_fetch_members() {
    global $wpdb;
    $members = $wpdb->get_results("
        SELECT 
            DISTINCT m.*, 
            u.user_login, 
            u.user_email,
            GROUP_CONCAT(t.type_name) AS type_names
        FROM 
            {$wpdb->prefix}slqs_members AS m
        LEFT JOIN 
            {$wpdb->prefix}users AS u ON m.user_id = u.ID
        LEFT JOIN 
            {$wpdb->prefix}slqs_member_group AS g ON m.id = g.member_id
        LEFT JOIN 
            {$wpdb->prefix}slqs_member_type AS t ON g.member_type_id = t.id
        GROUP BY 
            m.id
    ");
    $data = array();
    foreach ($members as $member) {
        $data[] = array(
            'id' => esc_html($member->id),
            'profile_photo' => esc_html($member->profile_photo),
            'full_name' => esc_html($member->first_name . ' ' . $member->last_name),
            'member_id' => esc_html($member->user_login),
            'email' => esc_html($member->user_email),
            'type_name' => esc_html($member->type_names),
            'status' => esc_html($member->status),
            'approve_link' => admin_url('admin-post.php?action=approve_member&member_id=' . $member->id)
        );
        // Add Edit and Delete links
        // $member_data['action'] = '<a href="' . admin_url('admin.php?page=slqs-edit-member&member_id=' . $member->id) . '">Edit</a> | 
        //                           <a href="' . admin_url('admin-post.php?action=delete_member&member_id=' . $member->id) . '" onclick="return confirm(\'Are you sure you want to delete this member?\');">Delete</a>';

    }

    wp_send_json(array('data' => $data));
}
add_action('wp_ajax_fetch_members', 'slqs_fetch_members');

// Admin: Approve member
function slqs_approve_member() {
    if (isset($_GET['member_id'])) {
        global $wpdb;
        $member_id = intval($_GET['member_id']);
        $wpdb->update("{$wpdb->prefix}slqs_members", array('status' => 'approved'), array('id' => $member_id));

        // Send approval email
        $member = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}slqs_members WHERE id = %d", $member_id));
        wp_mail($member->email, 'Membership Approved', 'Your membership has been approved. Member ID: ' . $member->member_id);

        wp_redirect(admin_url('admin.php?page=slqs-members'));
        exit;
    }
}
add_action('admin_post_approve_member', 'slqs_approve_member');

// Admin: Delete member
function slqs_delete_member() {
    if (isset($_GET['member_id'])) {
        global $wpdb;
        $member_id = intval($_GET['member_id']);

        $wpdb->delete("{$wpdb->prefix}slqs_members", array('id' => $member_id));

        wp_redirect(admin_url('admin.php?page=slqs-members'));
        exit;
    }
}
add_action('admin_post_delete_member', 'slqs_delete_member');