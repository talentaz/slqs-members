<?php
 require_once __DIR__ . '/fpdf/fpdf.php';
 require_once __DIR__ . '/fpdi2/src/autoload.php';

 use setasign\Fpdi\Fpdi;

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
    echo '<button id="exportExcel" class="btn btn-primary">Export to Excel</button>';
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
            m.*, 
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
            m.id, u.user_login, u.user_email
        ORDER BY 
            m.id DESC
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
        $wpdb->update("{$wpdb->prefix}slqs_members", array('status' => 'APPROVED'), array('id' => $member_id));

        // Send approval email
        $member = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}slqs_members WHERE id = %d", $member_id));

        if ($member) {
            // Step 2: Get the user ID from the member record
            $user_id = $member->user_id; // Assuming 'user_id' is the column name in slqs_members
        
            // Step 3: Get the user data from the wp_users table
            $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}users WHERE ID = %d", $user_id));
            
            $pdf_path = generate_member_pdf( $user, $member);
            $wpdb->update(
                "{$wpdb->prefix}slqs_members",
                array('pdf_path' => $pdf_path), // Update the profile_photo field with the URL
                array('id' => $member_id), // Where clause
                array('%s'), // Data format
                array('%d') // Where format
            );

            if ($user) {
                $subject = 'Welcome to SLQS-UAE - Membership Confirmation';
                $member_email_content =  file_get_contents( __DIR__ . '/confirmation.txt');
                $message = str_replace('[Membership Number]', $user->user_login, $member_email_content);
                $message = str_replace('[Registered Email]', $user->user_email, $message);
               
                if (wp_mail($user->user_email, $subject, $message)) {
                    wp_redirect(admin_url('admin.php?page=slqs-members'));
                    exit;
                } else {
                    // Capture the last error message from PHPMailer
                    global $phpmailer;
                    $error_message = $phpmailer->ErrorInfo;
                
                    // Send a JSON error response with the error message
                    wp_send_json_error('Cannot send email: ' . $error_message);
                }
            } else {
                echo "No user found with ID: " . $user_id;
            }
        } else {
            echo "No member found with ID: " . $member_id;
        }

        
        // wp_mail($member->email, 'Membership Approved', 'Your membership has been approved. Member ID: ' . $member->member_id);

       
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


function generate_member_pdf($user, $member) {

    if (!$member || !$user) {
        return; // Handle the case where member or user is not found
    }

    // Initialize FPDI
    $pdf = new Fpdi();

    // Define the blank template
    $blankTemplate = __DIR__ . '/assets/Template-01.pdf';
    
    // Import the blank template as the base
    $pageCount = $pdf->setSourceFile($blankTemplate);
    $templatePage = $pdf->importPage(1);

    // Add a new page with the same size as the imported page
    $pdf->AddPage($pdf->getTemplateSize($templatePage)['orientation'], $pdf->getTemplateSize($templatePage));

    // Use the imported template
    $pdf->useTemplate($templatePage, 0, 0, 210, 297); // Adjust dimensions if necessary

    // Set font
    $pdf->AddFont('IBMPlexSansRegular','','IBMPlexSans-Regular.php');
    $pdf->AddFont('IBMPlexSansMedium','','IBMPlexSans-Medium.php');
    $pdf->SetFont('IBMPlexSansRegular','',10);

    // Add member name
    $pdf->SetXY(25, 85); // X = 25mm, Y = 85mm
    $pdf->Write(5, $member->first_name . ' ' . $member->last_name); // Use user's display name

    // Add text "Membership No."
    $pdf->SetXY(25, 110); // Adjust Y position as needed
    $pdf->Write(5, $user->user_login); // Assuming 'membership_number' is a column in slqs_members
    // Add membership details
    $pdf->SetXY(25, 140); // X = 25mm, Y = 140mm
    $pdf->Write(5, date('d. m. Y', strtotime($member->created_at))); // Assuming 'issued_date' is a column in slqs_members

    $pdf->SetFont('IBMPlexSansRegular','',7);
    $pdf->SetXY(46, 140); // X = 46mm, Y = 140mm
    $pdf->Write(5, "(Issued date)");

    $pdf->SetFont('IBMPlexSansMedium','',10.5);
    // Add text "Dear [User's Name],"
    $pdf->SetXY(28, 183.2); // X = 28mm, Y = 183.2mm
    $pdf->Write(5, $member->first_name . ' ' . $member->last_name . ",");

    // Add additional text
    $pdf->SetXY(18.5, 255); // X = 18.5mm, Y = 255mm
    $pdf->Write(5, date('d F Y', strtotime($member->created_at))); // Format the date
    
    // Define the upload directory
    $upload_dir = wp_upload_dir();
    $pdf_file_path = $upload_dir['path'] . '/' . $user->user_login . '.pdf'; // Save with a unique name
    $pdf_file_url = $upload_dir['url'] . '/' . $user->user_login . '.pdf'; // Save with a unique name
    // Output the PDF to a file
    $pdf->Output($pdf_file_path, 'F'); // 'F' for file output
    // Optionally, return the file path or perform further actions
    return $pdf_file_url; // Return the path of the saved PDF
    
}