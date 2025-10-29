<div class="member-wrapper">
    <div class="member-description">
        <div class="member-label">
        Academic Qualifications 
        </div>
        <div class="member-value">
            <?php echo esc_html($member_spec->academic_qualifications); ?>
        </div>
    </div>
    <div class="member-description">
        <div class="member-label">
        Professional Qualifications 
        </div>
        <div class="member-value">
            <?php echo esc_html($member_spec->professional_qualifications); ?>
        </div>
    </div>
    <div class="member-description">
        <div class="member-label">
        Bio
        </div>
        <div class="member-value">
            <?php echo esc_html($member_spec->bio); ?>
        </div>
    </div>
</div>
<?php
    if (is_user_logged_in()) {
        $current_user_id = get_current_user_id(); 
        if ($current_user_id == $member->user_id) {
      ?>
      <div class="mt-2">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#memberCPD">
                Edit
            </button>
      </div>
      <div class="modal fade" id="memberCPD" tabindex="-1" role="dialog" aria-labelledby="memberCPDTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit CPD</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form></form>
      <form class="custom-validation" id="slqs-member-cpd-edit-form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="slqs_cpd_edit" value="1">
        <input type="hidden" name="member_id" value="<?php echo esc_attr( $member_spec->member_id ); ?>">
        <input type="hidden" name="member_type_id" value="<?php echo esc_attr( $member_spec->member_type_id ); ?>">
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Academic Qualifications</label>
                <textarea class="form-control" name="academic_qualifications"><?php echo esc_attr( $member_spec->academic_qualifications ); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Professional Qualifications</label>
                <textarea class="form-control" name="professional_qualifications"><?php echo esc_attr( $member_spec->professional_qualifications ); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Bio</label>
                <textarea class="form-control" name="bio"><?php echo esc_attr( $member_spec->bio ); ?></textarea>
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