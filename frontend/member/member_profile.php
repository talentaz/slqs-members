<div class="member-wrapper">
    <div class="member-description">
        <div class="member-label">
        First Name
        </div>
        <div class="member-value">
            <?php echo esc_html($member->first_name); ?>
        </div>
    </div>
    <div class="member-description">
        <div class="member-label">
        Last name
        </div>
        <div class="member-value">
            <?php echo esc_html($member->last_name); ?>
        </div>
    </div>
    <div class="member-description">
        <div class="member-label">
        Date of Birth
        </div>
        <div class="member-value">
            <?php echo esc_html($member->date_of_birth); ?>
        </div>
    </div>
    <div class="member-description">
        <div class="member-label">
        Emirate of Residence in UAE
        </div>
        <div class="member-value">
        <?php 
            $emirateId = $member->emirate_of_location; 

            $locationMap = [];
            foreach ($locations as $location) {
                $locationMap[$location->id] = esc_html($location->location);
            }

            if (array_key_exists($emirateId, $locationMap)) {
                echo $locationMap[$emirateId];
            } else {
                echo 'Location not found'; // Fallback if the ID does not exist
            }
        ?>
        </div>
    </div>
    <div class="member-description">
        <div class="member-label">
        Current Address in UAE
        </div>
        <div class="member-value">
            <?php echo esc_html($member->current_address); ?>
        </div>
    </div>
    <div class="member-description">
        <div class="member-label">
        Date of Birth
        </div>
        <div class="member-value">
            <?php echo esc_html($member->date_of_birth); ?>
        </div>
    </div>
    <div class="member-description">
        <div class="member-label">
        Mobile Number (Primary)
        </div>
        <div class="member-value">
            <?php echo esc_html($member->mobile_primary); ?>
        </div>
    </div>
    <div class="member-description">
        <div class="member-label">
        Mobile Number (Alternative)
        </div>
        <div class="member-value">
            <?php echo esc_html($member->mobile_alternative); ?>
        </div>
    </div>
    <div class="member-description">
        <div class="member-label">
        E-mail Address (Primary)
        </div>
        <div class="member-value">
            <?php echo esc_html($member->user_email); ?>
        </div>
    </div>
    <div class="member-description">
        <div class="member-label">
        E-mail Address (Alternative)
        </div>
        <div class="member-value">
            <?php echo esc_html($member->email_alternative); ?>
        </div>
    </div>
    <div class="member-description">
        <div class="member-label">
        Joining Date for the 1st Job in UAE
        </div>
        <div class="member-value">
            <?php echo esc_html($member->joining_date); ?>
        </div>
    </div>
    <h5 class="mt-2">Employment Information</h5>
    <div class="member-description">
        <div class="member-label">
        Current Employer
        </div>
        <div class="member-value">
            <?php echo esc_html($member->current_employer); ?>
        </div>
    </div>
    <div class="member-description">
        <div class="member-label">
        Employer Address 
        </div>
        <div class="member-value">
            <?php echo esc_html($member->employer_address); ?>
        </div>
    </div>
    <div class="member-description">
        <div class="member-label">
        Emirate of Residence in UAE
        </div>
        <div class="member-value">
            <?php 
                $currentWordId = $member->current_work_location; 

                $locationMap = [];
                foreach ($locations as $location) {
                    $locationMap[$location->id] = esc_html($location->location);
                }
                
                if (array_key_exists($currentWordId, $locationMap)) {
                    echo $locationMap[$currentWordId];
                } else {
                    echo 'Location not found'; // Fallback if the ID does not exist
                }
            ?>
        </div>
    </div>
    <div class="member-description">
        <div class="member-label">
        Company Phone Number
        </div>
        <div class="member-value">
            <?php echo esc_html($member->company_phone); ?>
        </div>
    </div>
    <div class="member-description">
        <div class="member-label">
        Company E-mail Address
        </div>
        <div class="member-value">
            <?php echo esc_html($member->company_email); ?>
        </div>
    </div>
    <div class="member-description">
        <div class="member-label">
        Current Position
        </div>
        <div class="member-value">
            <?php echo esc_html($member->current_position); ?>
        </div>
    </div>
    <h5 class="mt-2">Emergency Contact <br> Name and Relationship to Applicant</h5>
    <div class="member-description">
        <div class="member-label">
        Name
        </div>
        <div class="member-value">
            <?php echo esc_html($member->emergency_contact_name); ?>
        </div>
    </div>
    <div class="member-description">
        <div class="member-label">
        Relationship to Applicant 
        </div>
        <div class="member-value">
            <?php echo esc_html($member->emergency_contact_relationship); ?>
        </div>
    </div>
    <div class="member-description">
        <div class="member-label">
        Address
        </div>
        <div class="member-value">
            <?php echo esc_html($member->emergency_contact_address); ?>
        </div>
    </div>
    <div class="member-description">
        <div class="member-label">
        Mobile Number 
        </div>
        <div class="member-value">
            <?php echo esc_html($member->emergency_contact_mobile); ?>
        </div>
    </div>
    <div class="member-description">
        <div class="member-label">
        E-mail 
        </div>
        <div class="member-value">
            <?php echo esc_html($member->emergency_contact_email); ?>
        </div>
    </div>
    
    
</div>
<?php
    if (is_user_logged_in()) {
        $current_user_id = get_current_user_id(); 
        if ($current_user_id == $member->user_id) {
      ?>
      <div class="mt-2">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#memberProfile">
                Edit
            </button>
      </div>

<!-- member modal section -->
<div class="modal fade" id="memberProfile" tabindex="-1" role="dialog" aria-labelledby="memberProfileTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Member</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="custom-validation" id="slqs-member-edit-form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="slqs_edit" value="1">
        <input type="hidden" name="member_id" value="<?php echo esc_attr( $member->id ); ?>">
        <div class="modal-body">
            <div>
            <div class="picture-container">
                <div class="picture">
                    <img src="<?php echo $profile_photo; ?>" class="picture-src" id="wizardPicturePreview" title="">
                    <input type="file" id="wizard-picture" name="image" class="">
                </div>
            </div>
            </div>
            <div class="mb-3">
                <label class="form-label">First name *</label>
                <input type="text" class="form-control" required name="first_name" value="<?php echo esc_attr( $member->first_name ); ?>"/>
            </div>
            <div class="mb-3">
                <label class="form-label">Last name *</label>
                <input type="text" class="form-control" required name="last_name" value="<?php echo esc_attr( $member->last_name );?>" />
            </div>
            <div class="mb-3">
                <label>Date of Birth *</label>
                <input type="date" class="form-control" min="1900-01-01" max="9999-12-31" required name="date_of_birth" value="<?php echo esc_attr( $member->date_of_birth );?>" >
            </div>
            <div class="mb-3">
                <label>Emirate of Residence in UAE *</label>
                <?php echo slqs_get_emirates_dropdown("emirate_of_location", $member->emirate_of_location); ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Current Address in UAE *</label>
                <input type="text" class="form-control" required name="current_address" value="<?php echo esc_attr( $member->current_address ); ?>"/>
            </div>
            <div class="mb-3">
                <label for="input-mask" class="form-label">Mobile Number * (Primary) +971-XXX-XXX-XXX</label>
                <input class="form-control input-mask" required data-inputmask="'mask': '+(999)-999-999-999'" name="mobile_primary" value="<?php echo esc_attr( $member->mobile_primary ); ?>" />
            </div>
            <div class="mb-3">
                <label class="form-label">Mobile Number (Alternative) +971-XXX-XXX-XXX</label>
                <input class="form-control input-mask" data-inputmask="'mask': '+(999)-999-999-999'" name="mobile_alternative" value="<?php echo esc_attr( $member->mobile_alternative ); ?>"/>
            </div>
            <div class="mb-3">
                <label class="form-label">E-mail Address * (Primary)</label>
                <input type="email" class="form-control" required name="email" value="<?php echo esc_attr( $member->user_email ); ?>"/>
            </div>
            <div class="mb-3">
                <label class="form-label">E-mail Address (Alternative)</label>
                <input type="text" class="form-control" name="email_alternative" value="<?php echo esc_attr( $member->email_alternative ); ?>"/>
            </div>
            <div class="mb-3">
                <label>Joining Date for the 1st Job in UAE *</label>
                <input type="date" class="form-control" min="1900-01-01" max="9999-12-31" required name="joining_date" value="<?php echo esc_attr( $member->joining_date ); ?>" />
            </div>

            <h5>Employment Information</h5>
            <div class="">
                <label class="form-label">Current Employer *</label>
                <input type="text" class="form-control" required name="current_employer" value="<?php echo esc_attr( $member->current_employer ); ?>"/>
            </div>
            <div class="mb-3">
                <label class="form-label">Employer Address *</label>
                <input type="text" class="form-control" required name="employer_address"value="<?php echo esc_attr( $member->employer_address ); ?>"/>
            </div>
            <div class="mb-3">
                <label>Emirate of Residence in UAE *</label>
                <?php echo slqs_get_emirates_dropdown("current_work_location", $member->current_work_location); ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Company Phone Number * (+971-X-XXX-XXXX)</label>
                <!-- <input type="text" class="form-control"  required name="company_phone"/> -->
                <input class="form-control input-mask" data-inputmask="'mask': '+(999)-9-999-9999'" required name="company_phone"  value="<?php echo esc_attr( $member->company_phone ); ?>"/>
            </div>
            <div class="mb-3">
                <label class="form-label">Company E-mail Address *</label>
                <input type="email" class="form-control" required name="company_email" value="<?php echo esc_attr( $member->company_email ); ?>"/>
            </div>
            <div class="mb-3">
                <label class="form-label">Current Position *</label>
                <input type="text" class="form-control" required  name="current_position" value="<?php echo esc_attr( $member->current_position ); ?>"/>
            </div>

            <h5>Emergency Contact <br> Name and Relationship to Applicant</h5>
            <div class="">
                <label class="form-label">Name *</label>
                <input type="text" class="form-control" required name="emergency_contact_name" value="<?php echo esc_attr( $member->emergency_contact_name );?>"/>
            </div>
            <div class="mb-3">
                <label class="form-label">Relationship to Applicant *</label>
                <input type="text" class="form-control" required name="emergency_contact_relationship" value="<?php echo esc_attr( $member->emergency_contact_relationship );?>"/>
            </div>
            <div class="mb-3">
                <label class="form-label">Address *</label>
                <input type="text" class="form-control" required name="emergency_contact_address" value="<?php echo esc_attr( $member->emergency_contact_address );?>"/>
            </div>
            <div class="mb-3">
                <label class="form-label">Mobile Number * (+971-XXX-XXX-XXX)</label>
                <input class="form-control input-mask" data-inputmask="'mask': '+(999)-999-999-999'" required name="emergency_contact_mobile" value="<?php echo esc_attr( $member->emergency_contact_mobile );?>"/>
            </div>
            <div class="mb-3">
                <label class="form-label">E-mail *</label>
                <input type="email" class="form-control" required name="emergency_contact_email" value="<?php echo esc_attr( $member->emergency_contact_email );?>"/>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary registerButton">Update</button>
            <button class="btn btn-primary loadingButton" disabled style="display:none;">
            <i class="bx bx-loader bx-spin font-size-16 align-middle me-2"></i> Update
        </button>
        </div>
      </from>
    </div>
  </div>
</div>

<?php  
    
        }
    } 
?>