<?php
function member_detail_shortcode() {
    global $wpdb;
    // Get the member ID from the URL
    $member_id = isset($_GET['member_id']) ? intval($_GET['member_id']) : 0;

    // Start output buffering
    $member = $wpdb->get_row($wpdb->prepare("
        SELECT m.*, u.user_email, u.user_login
        FROM {$wpdb->prefix}slqs_members AS m
        LEFT JOIN {$wpdb->prefix}users AS u ON m.user_id = u.ID
        WHERE m.id = %d
    ", $member_id));

    $member_specs = $wpdb->get_results($wpdb->prepare("
        SELECT *
        FROM {$wpdb->prefix}slqs_member_special_info
        WHERE member_id = %d
    ", $member->id));

    if ($member) {
        $profile_photo = !empty($member->profile_photo) ? esc_url($member->profile_photo) : plugins_url('assets/images/avatar.png', __FILE__);
        ?>

        <div class="header-section">
            <div class="cover-img">
                <img src="https://slqsuae.org/temp/wp-content/uploads/2023/09/FB-2.jpeg" alt="">
            </div>
            <div class="profile">
                <div class="profile-img">
                    <img src="<?php echo $profile_photo; ?>" alt="" >
                </div>
                <!-- <div class="picture-container">
                    <div class="picture">
                        <img src="<?php echo $profile_photo; ?>" class="picture-src" id="wizardPicturePreview" title="">
                        <input type="file" id="wizard-picture" name="file" class="">
                    </div>
                </div> -->
                <!-- <div class="profile-img picture-container">
                    <img src="<?php echo $profile_photo; ?>" alt="" >
                    <input type="file" id="wizard-picture" name="file" class="">
                </div> -->
                <div class="profile-title">
                    <h5><?php echo esc_html($member->first_name . ' ' . $member->last_name); ?></h5>
                    <p>MemberShip Number:<?php echo esc_html($member->user_login); ?></p>
                    <a href="#">SLQS Members</a>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active" id="v-pills-member-tab" data-toggle="pill" data-target="#v-pills-member" type="button" role="tab" aria-controls="v-pills-member" aria-selected="true">Member Profile</button>
                    <?php if ($member_specs): ?>
                        <?php foreach ($member_specs as $member_spec): ?>
                            <?php if ($member_spec->member_type_id == 2): ?>
                                <button class="nav-link" id="v-pills-cpd-tab" data-toggle="pill" data-target="#v-pills-cpd" type="button" role="tab" aria-controls="v-pills-cpd" aria-selected="false">CPD Speaker</button>
                            <?php endif; ?>
                            <?php if ($member_spec->member_type_id == 3): ?>
                                <button class="nav-link" id="v-pills-rics-tab" data-toggle="pill" data-target="#v-pills-rics" type="button" role="tab" aria-controls="v-pills-rics" aria-selected="false">RICS/AIQA Assessors</button>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-member" role="tabpanel" aria-labelledby="v-pills-member-tab">
                        <?php
                        include('member/member_user_profile.php');
                        ?>
                    </div>
                    <?php if ($member_specs): ?>
                        <?php foreach ($member_specs as $member_spec): ?>
                            <?php if ($member_spec->member_type_id == 2): ?>
                                <div class="tab-pane fade" id="v-pills-cpd" role="tabpanel" aria-labelledby="v-pills-cpd-tab">
                                    <?php include('member/cpd.php'); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($member_spec->member_type_id == 3): ?>
                                <div class="tab-pane fade" id="v-pills-rics" role="tabpanel" aria-labelledby="v-pills-rics-tab">
                                    <?php include('member/rics.php'); ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo '<p>Member not found.</p>';
    }

    // Return the output buffer content
    return ob_get_clean();
}

// Register the shortcode
add_shortcode('member_detail', 'member_detail_shortcode');
?>
