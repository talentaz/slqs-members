<?php

function member_login_form() {
    ob_start();
    // Check if the user is already logged in
    if (is_user_logged_in()) {
        // Redirect to the profile page if already logged in
        wp_safe_redirect(home_url('/my-profile'));
        exit;
    }

    // Handle login form submission
    if (isset($_POST['custom_login_submit'])) {
        $username = sanitize_user($_POST['user_login']);
        $password = sanitize_text_field($_POST['user_pass']);
        $secure_cookie = is_ssl();

        // Check if the username is an email
        if (is_email($username)) {
            $user = get_user_by('email', $username);
        } else {
            $user = get_user_by('login', $username);
        }
        if ($user) {
            // Verify the password
            if (wp_check_password($password, $user->data->user_pass, $user->ID)) {
                // Debugging statements removed
                $creds = array('user_login' => $user->data->user_login, 'user_password' => $password);
                $user = wp_signon($creds, $secure_cookie);
                if ( is_wp_error( $user ) ) {
                    echo $user->get_error_message();
                }

                $url = home_url('wp-admin');
                // Redirect after login
                wp_safe_redirect(home_url('/my-profile'));
                exit; // Ensure the script stops after the redirect
            } else {
                // Incorrect password
                $redirect_url = add_query_arg('errors', 'incorrect_password', wp_login_url());
                wp_safe_redirect(esc_url_raw($redirect_url));
                exit;
            }
        } else {
            // Invalid username
            $redirect_url = add_query_arg('errors', 'invalid_username', wp_login_url());
            wp_safe_redirect(esc_url_raw($redirect_url));
            exit;
        }
        // if ($user) {
        //     // Verify the password
        //     if (wp_check_password($password, $user->data->user_pass, $user->ID)) {
        //         $creds = array('user_login' => $user->data->user_login, 'user_password' => $password);
        //         $user = wp_signon($creds, $secure_cookie);
        //         echo "123"; exit;
        //         // Redirect after login
        //         wp_safe_redirect(home_url('/my-profile'));
        //         exit;
        //     } else {
        //         // Incorrect password
        //         $redirect_url = add_query_arg('errors', 'incorrect_password', wp_login_url());
        //         wp_safe_redirect(esc_url_raw($redirect_url));
        //         exit;
        //     }
        // } else {
        //     // Invalid username
        //     $redirect_url = add_query_arg('errors', 'invalid_username', wp_login_url());
        //     wp_safe_redirect(esc_url_raw($redirect_url));
        //     exit;
        // }
    }
    ?>

    <?php
      if($_GET['checkemail']=='confirm'){
        ?> <p><?php
      echo "A password reset link has been sent to your email. If you don’t see it in your inbox, please check your spam or junk folder.\n\n";
      echo "<br>";
      ?> <p><?php 
      }
    ?>
    <form method="post" class="custom-login-form">
        <p>
            <label for="user_login">Username or Email</label>
            <input type="text" name="user_login" id="user_login" required>
        </p>
        <p>
            <label for="user_pass">Password</label>
            <input type="password" name="user_pass" id="user_pass" required>
        </p>
        <table style="width: 15%; ">
            <tr>
                <td><input type="checkbox" name="rememberme" id="rememberme" value="forever" ></td>
                <td><label for="rememberme">Remember Me</label></td>
            </tr>
        </table>
        <p>
            <input type="submit" name="custom_login_submit" value="Log In">
        </p>
        <p>
            <a href="<?php echo wp_lostpassword_url(); ?>">Forgot Password?</a>
        </p>
    </form>

    <?php


    return ob_get_clean();
    //[profilegrid_login]
}

// Register the shortcode
add_shortcode('member_login', 'member_login_form');
