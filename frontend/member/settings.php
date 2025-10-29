<?php
    if (is_user_logged_in()) {
        $current_user_id = get_current_user_id(); 
        if ($current_user_id == $member->user_id) {
      ?>
      <div class="mt-2">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#memberPassword">
                Change Password
            </button>
      </div>
      <div class="modal fade" id="memberPassword" tabindex="-1" role="dialog" aria-labelledby="memberPasswordTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Change Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form></form>
      <form class="custom-validation" id="slqs-member-password-edit-form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="slqs_password_edit" value="1">
        <input type="hidden" name="user_id" value="<?php echo esc_attr( $current_user_id ); ?>">
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Change Password</label>
                <div>
                    <input type="password" id="pass2" class="form-control" required
                        placeholder="Password" name="password"/>
                </div>
                <div class="mt-2">
                    <input type="password" class="form-control" required
                        data-parsley-equalto="#pass2" placeholder="Re-Type Password" />
                </div>
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