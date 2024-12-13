<?php
add_action('wp_enqueue_scripts', 'slqs_enqueue_scripts');
function slqs_enqueue_scripts() {
    slqs_enqueue_scripts_and_styles();
}


function determine_decade($year) {
    if ($year <= 1994) {
        return 1; // Decade 1
    } elseif ($year >= 1995 && $year <= 2004) {
        return 2; // Decade 2
    } elseif ($year >= 2005 && $year <= 2014) {
        return 3; // Decade 3
    } elseif ($year >= 2015 && $year <= 2024) {
        return 4; // Decade 4
    } elseif ($year >= 2025 && $year <= 2034) {
        return 5; // Decade 5
    } else {
        return "Year out of range"; // Handle years beyond 2034
    }
}

function calculate_ytd($date) {
    return $date->format('z') + 1; // Day of the year (0-indexed, so add 1)
}

function generate_membership_number($joining_date) {
    global $wpdb;
    // Convert joining date to DateTime object
    $date = DateTime::createFromFormat('Y-m-d', $joining_date);
    
    if (!$date) {
        return "Invalid date format.";
    }

    // Extract year and calculate components
    $year = $date->format('Y'); // Full year
    $year_short = $date->format('y'); // Last two digits of the year
    $day_of_year = calculate_ytd($date); // Day of the year

    // Determine decade
    $decade_digit = determine_decade($year); 

    // Format YTD to 3 digits
    $ytd_formatted = str_pad($day_of_year, 3, '0', STR_PAD_LEFT); // Pad with zeros

    $last_number = 1; // Start with the last number as 1
    $membership_number = sprintf("%d%s%s%d", $decade_digit, $year, $ytd_formatted, $last_number);

    // Check if the membership number already exists
    $existing_member = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}users WHERE user_login = %s", $membership_number));

    while ($existing_member) {
        // Increment the last number
        $last_number++;
        // Generate a new membership number
        $membership_number = sprintf("%d%s%s%d", $decade_digit, $year, $ytd_formatted, $last_number);
        // Check again if this new membership number exists
        $existing_member = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}users WHERE user_login = %s", $membership_number));
    }

    return $membership_number;
}

// Frontend: Shortcode for registration form
function slqs_registration_form() {
    ob_start();
    ?>
    <form class="custom-validation" id="slqs-registration-form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="slqs_register" value="1">
         <h5>Applicant Information</h5>
         <div class="row">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label">First name *</label>
                    <input type="text" class="form-control" required name="first_name"/>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                     <label class="form-label">Last name *</label>
                    <input type="text" class="form-control" required name="last_name"/>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label>Date of Birth *</label>
            <input type="date" class="form-control" required name="date_of_birth">
        </div>
        <div class="mb-3">
            <label>Emirate of Residence in UAE *</label>
            <?php echo slqs_get_emirates_dropdown("emirate_of_location"); ?>
        </div>
        <div class="mb-3">
            <label class="form-label">Current Address in UAE *</label>
            <input type="text" class="form-control" required name="current_address"/>
        </div>
        <div class="mb-3">
            <label for="input-mask" class="form-label">Mobile Number * (Primary) +971-XXX-XXX-XXX</label>
            <input class="form-control input-mask" required data-inputmask="'mask': '+(999)-999-999-999'" name="mobile_primary" value="+971"/>
        </div>
        <div class="mb-3">
            <label class="form-label">Mobile Number (Alternative) +971-XXX-XXX-XXX</label>
            <input class="form-control input-mask" data-inputmask="'mask': '+(999)-999-999-999'" name="mobile_alternative" value="+971"/>
        </div>
        <div class="mb-3">
            <label class="form-label">E-mail Address * (Primary)</label>
            <input type="email" class="form-control" required name="email"/>
        </div>
        <div class="mb-3">
            <label class="form-label">E-mail Address (Alternative)</label>
            <input type="text" class="form-control" name="email_alternative"/>
        </div>
        <div class="mb-3">
            <label>Joining Date for the 1st Job in UAE *</label>
            <input type="date" class="form-control" required name="joining_date">
        </div>

        <h5>Employment Information</h5>
        <div class="">
            <label class="form-label">Current Employer *</label>
            <input type="text" class="form-control" required name="current_employer"/>
        </div>
        <div class="mb-3">
            <label class="form-label">Employer Address *</label>
            <input type="text" class="form-control" required name="employer_address"/>
        </div>
        <div class="mb-3">
            <label>Emirate of Residence in UAE *</label>
            <?php echo slqs_get_emirates_dropdown("current_work_location"); ?>
        </div>
        <div class="mb-3">
            <label class="form-label">Company Phone Number * (+971-X-XXX-XXXX)</label>
            <!-- <input type="text" class="form-control"  required name="company_phone"/> -->
            <input class="form-control input-mask" data-inputmask="'mask': '+(999)-9-999-9999'" required name="company_phone" value="+971"/>
        </div>
        <div class="mb-3">
            <label class="form-label">Company E-mail Address *</label>
            <input type="email" class="form-control" required name="company_email"/>
        </div>
        <div class="mb-3">
            <label class="form-label">Current Position *</label>
            <input type="text" class="form-control" required  name="current_position"/>
        </div>

        <h5>Employment Information <br> Name and Relationship to Applicant</h5>
        <div class="">
            <label class="form-label">Name *</label>
            <input type="text" class="form-control" required name="emergency_contact_name"/>
        </div>
        <div class="mb-3">
            <label class="form-label">Relationship to Applicant *</label>
            <input type="text" class="form-control" required name="emergency_contact_relationship"/>
        </div>
        <div class="mb-3">
            <label class="form-label">Address *</label>
            <input type="text" class="form-control" required name="emergency_contact_address"/>
        </div>
        <div class="mb-3">
            <label class="form-label">Mobile Number * (+971-XXX-XXX-XXX)</label>
            <input class="form-control input-mask" data-inputmask="'mask': '+(999)-999-999-999'" required name="emergency_contact_mobile" value="+971"/>
        </div>
        <div class="mb-3">
            <label class="form-label">E-mail *</label>
            <input type="email" class="form-control" required name="emergency_contact_email"/>
        </div>

        <h5>Referee</h5>
        <div class="mb-3">
            <label class="form-label">Name of SLQS Member *</label>
            <input type="text" class="form-control" required name="referee_name"/>
        </div>
        <div class="mb-3">
            <label class="form-label">Membership No *</label>
            <input type="text" class="form-control" required name="referee_membership_no"/>
        </div>
        <div class="mb-3">
            <label class="form-label">Mobile Number * (+971-XXX-XXX-XXX)</label>
            <!-- <input type="text" class="form-control"  required name="referee_mobile"/> -->
            <input class="form-control input-mask" data-inputmask="'mask': '+(999)-999-999-999'" required name="referee_mobile" value="+971"/>
        </div>
        <h5>Declaration</h5>
        <p>I certify that the information and particulars I have given in making this application are true and accurate. I also agree that the Central Committee of Sri Lankan Quantity Surveyors United Arab Emirates (SLQS-UAE) has the full authority to decide on my membership.</p>
        <!-- <input type="file" name="profile_photo" accept="image/*" required> -->
        
        
        <button type="submit" class="btn btn-primary" id="registerButton">Register</button>
    
        <!-- Loading Button (initially hidden) -->
        <button class="btn btn-primary" id="loadingButton" disabled style="display:none;">
            <i class="bx bx-loader bx-spin font-size-16 align-middle me-2"></i> Register
        </button>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('slqs_registration', 'slqs_registration_form');

function formatted_mobile($phone) {
    $formatted_mobile = str_replace(['(', ')', '-'], '', $phone);
    return $formatted_mobile;
}

// Action for logged-in users
add_action('wp_ajax_slqs_register', 'slqs_handle_registration');

// Action for non-logged-in users
add_action('wp_ajax_nopriv_slqs_register', 'slqs_handle_registration');
// Frontend: Handle form submission
function slqs_handle_registration() {
    if (isset($_POST['slqs_register'])) {
        global $wpdb;
        // Sanitize and retrieve form data
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
        
        // Check if email already exists
        $existing_member = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}users WHERE user_email = %s", $email));
        if ($existing_member) {
            wp_send_json_error('Email already exists. Please use a different email.');
        }

        $membership_number = generate_membership_number($joining_date);
        $password = wp_hash_password('12345');

        // Step 4: Insert user data into the users table
        $user_data = array(
            'user_email'    => $email,
            'user_login'    => $membership_number,
            'user_nicename' => $membership_number,
            'user_pass'     => $password,
            'display_name'  => $membership_number,
            'user_registered' => current_time('mysql'), // Set the registration time
        );

        $user_inserted = $wpdb->insert("{$wpdb->prefix}users", $user_data);
        $user_id = 0;
        if ($user_inserted) {
            $user_id = $wpdb->insert_id; 
        }
     
        // Handle file upload (uncomment if needed)
        // $profile_photo = $_FILES['profile_photo'];
        // $upload = wp_handle_upload($profile_photo, array('test_form' => false));
        // if (isset($upload['error'])) {
        //     echo "Error uploading file: " . $upload['error'];
        //     return;
        // }

        // Insert member data into slqs_members table
        $member_inserted = $wpdb->insert(
                                "{$wpdb->prefix}slqs_members",
                                array(
                                    'user_id' => $user_id, // Generate unique user ID
                                    'first_name' => $first_name,
                                    'last_name' => $last_name,
                                    'date_of_birth' => $date_of_birth,
                                    'status' => 'PENDING',

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
                                    // 'profile_photo' => $upload['url'] // Uncomment if handling file uploads
                                )
                            );

        $member_id = 0;
        if ($member_inserted) {
            $member_id = $wpdb->insert_id;
        } else {
            wp_send_json_error("Error creating member: " . $wpdb->last_error);
        }
        $wpdb->insert(
            "{$wpdb->prefix}slqs_member_group",
            array(
                'member_id' => $member_id, // Generate unique user ID
                'member_type_id' => 1
                 )
        );
        // Send verification email to referee
        $verification_link = site_url('/verify-referee?email=' . urlencode($email));
        wp_mail($email, 'Verify Member Details', "Please verify the member details by clicking this link: $verification_link");
       
        wp_send_json_success(array('message' => 'Thank you!', 'redirect' => home_url('/success')));
    } else {
        wp_send_json_error('Registration failed. Please try again.');
    }
}
