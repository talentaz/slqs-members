<?php
// Ensure this file is only accessed through WordPress admin
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}





// Get the member ID from the query string
$member_id = isset( $_GET['member_id'] ) ? intval( $_GET['member_id'] ) : 0;
// Fetch the member data from the database
$member = get_member_by_id( $member_id );
// echo "<pre>";
// print_r($member);
// echo "</pre>";

?>
<div class="wrap">
    <h1>Edit Member</h1>
    <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
        <input type="hidden" name="action" value="update_member">
        <input type="hidden" name="member_id" value="<?php echo $member->id; ?>">
        <table class="form-table">
            <tr>
                <th scope="row"><label>Member No</label></th>
                <td><input name="user_login" type="text" id="user_login" value="<?php echo esc_attr( $member->user_login ); ?>" class="regular-text" required readonly ></td>
            </tr>
            <tr>
                <th scope="row"><label>Member Type</label></th>
            <td><?php echo slqs_get_member_group("member_type_id", $member->member_type_ids); ?></td>
            <tr>
                <th scope="row"><label>First Name</label></th>
                <td><input name="first_name" type="text" id="first_name" value="<?php echo esc_attr( $member->first_name ); ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th scope="row"><label>Last name</label></th>
                <td><input type="text" class="regular-text" required name="last_name" value="<?php echo esc_attr( $member->last_name ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row"><label>Date of Birth</label></th>
                <td><input type="date" class="regular-text" required name="date_of_birth" value="<?php echo esc_attr( $member->date_of_birth ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row"><label>Emirate of Residence in UAE</label></th>
                <td><?php echo slqs_get_emirates_dropdown("emirate_of_location", $member->emirate_of_location); ?></td>
            </tr>
            <tr>
                <th scope="row"><label>Current Address in UAE</label></th>
                <td><input type="text" class="regular-text" required name="current_address" value="<?php echo esc_attr( $member->current_address ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row"><label>Mobile Number (Primary)</label></th>
                <td><input type="text" class="regular-text" required name="mobile_primary" value="<?php echo esc_attr( $member->mobile_primary ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row"><label>Mobile Number (Alternative)</label></th>
                <td><input type="text" class="regular-text" required name="mobile_alternative" value="<?php echo esc_attr( $member->mobile_alternative ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row"><label>E-mail Address (Primary)</label></th>
                <td><input type="email" class="regular-text" required readonly name="email" value="<?php echo esc_attr( $member->user_email ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row"><label>E-mail Address (Alternative)</label></th>
                <td><input type="email" class="regular-text" name="email_alternative" value="<?php echo esc_attr( $member->email_alternative ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row"><label>Joining Date for the 1st Job in UAE</label></th>
                <td><input type="date" class="regular-text" required name="joining_date" value="<?php echo esc_attr( $member->joining_date ); ?>"/></td>
            </tr>
            <tr>
                <th><h3>Employment Information</h3></th>
                <td></td>
            </tr>
            <tr>
                <th scope="row"><label>Current Employer</label></th>
                <td><input type="text" class="regular-text" required name="current_employer" value="<?php echo esc_attr( $member->current_employer ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row"><label>Employer Address</label></th>
                <td><input type="text" class="regular-text" required name="employer_address" value="<?php echo esc_attr( $member->employer_address ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row"><label>Emirate of Residence in UAE</label></th>
                <td><?php echo slqs_get_emirates_dropdown("current_work_location", $member->current_work_location); ?></td>
            </tr>
            <tr>
                <th scope="row"><label>Company Phone Number (Mandatory)</label></th>
                <td><input type="text" class="regular-text" required name="company_phone" value="<?php echo esc_attr( $member->company_phone ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row"><label>Company E-mail Address (Mandatory)</label></th>
                <td><input type="email" class="regular-text" required name="company_email" value="<?php echo esc_attr( $member->company_email ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row"><label>Current Position (Mandatory)</label></th>
                <td><input type="text" class="regular-text" required name="current_position" value="<?php echo esc_attr( $member->current_position ); ?>"/></td>
            </tr>

            <tr>
                <th><h3>Employment Information <br> Name and Relationship to Applicant</h3></th>
                
            </tr>
            <tr>
                <th scope="row"><label>Name</label></th>
                <td><input type="text" class="regular-text" required name="emergency_contact_name" value="<?php echo esc_attr( $member->emergency_contact_name ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row"><label>Relationship to Applicant</label></th>
                <td><input type="text" class="regular-text" required name="emergency_contact_relationship" value="<?php echo esc_attr( $member->emergency_contact_relationship ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row"><label>Address</label></th>
                <td><input type="text" class="regular-text" required name="emergency_contact_address" value="<?php echo esc_attr( $member->emergency_contact_address ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row"><label>Mobile Number</label></th>
                <td><input type="text" class="regular-text" required name="emergency_contact_mobile" value="<?php echo esc_attr( $member->emergency_contact_mobile ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row"><label>E-mail</label></th>
                <td><input type="email" class="regular-text" required name="emergency_contact_email" value="<?php echo esc_attr( $member->emergency_contact_email ); ?>"/></td>
            </tr>

            <tr>
                <td scope="row"><h3>Referee</h3></td>
            </tr>
            <tr>
                <th scope="row"><label>Name of SLQS Member (Mandatory)</label></th>
                <td><input type="text" class="regular-text" required name="referee_name" value="<?php echo esc_attr( $member->referee_name ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row"><label>Membership No (Mandatory)</label></th>
                <td><input type="text" class="regular-text" required name="referee_membership_no" value="<?php echo esc_attr( $member->referee_membership_no ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row"><label>Mobile Number (Mandatory)</label></th>
                <td><input type="text" class="regular-text" required name="referee_mobile" value="<?php echo esc_attr( $member->referee_mobile ); ?>"/></td>
            </tr>
        </table>
        <?php submit_button( 'Update Member' ); ?>
    </form>
</div>