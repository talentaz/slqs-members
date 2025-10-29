<?php
// add_action('wp_enqueue_scripts', 'slqs_enqueue_list_scripts');
// function slqs_enqueue_list_scripts() {
//     slqs_enqueue_scripts_and_styles();
// }


function slqs_list() {
    ob_start();
    ?>
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="search value 3 text above...">
    </div>
    <div id="memberList" class="row">
        <?php slqs_display_members(1); // Display the first page by default ?>
    </div>
    <!-- <div class="row">
        <div class="col-lg-12">
            <ul class="pagination pagination-rounded justify-content-center mt-3 mb-4 pb-1" id="pagination">
                <li class="page-item disabled" id="prevPage">
                    <a href="javascript:void(0);" class="page-link"><i class="mdi mdi-chevron-left"></i></a>
                </li>
                <li class="page-item active" data-page="1"><a href="javascript:void(0);" class="page-link">1</a></li>
                <li class="page-item" data-page="2"><a href="javascript:void(0);" class="page-link">2</a></li>
                <li class="page-item" data-page="3"><a href="javascript:void(0);" class="page-link">3</a></li>
                <li class="page-item" data-page="4"><a href="javascript:void(0);" class="page-link">4</a></li>
                <li class="page-item" data-page="5"><a href="javascript:void(0);" class="page-link">5</a></li>
                <li class="page-item" id="nextPage">
                    <a href="javascript:void(0);" class="page-link"><i class="mdi mdi-chevron-right"></i></a>
                </li>
            </ul>
        </div>
    </div> -->
    <?php
    return ob_get_clean();
}

add_action('wp_ajax_load_members', 'load_members');
add_action('wp_ajax_nopriv_load_members', 'load_members');

function load_members() {
    global $wpdb;

    // Get the keyword and page number from the AJAX request
    $keyword = isset($_POST['keyword']) ? sanitize_text_field($_POST['keyword']) : '';
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1; // Get the page number, default to 1

    // Call the slqs_display_members function with the page and search keyword
    slqs_display_members($page, $keyword);

    wp_die(); // This is required to terminate immediately and return a proper response
}

function slqs_display_members($page, $search = '') {
    global $wpdb;
    $members_per_page = 12;
    
    // Prepare the base query
    $base_query = "
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
    ";

    // If a search term is provided, modify the query to include a WHERE clause
    if (!empty($search)) {
        $base_query .= $wpdb->prepare(" WHERE m.first_name LIKE %s OR m.last_name LIKE %s OR u.user_email LIKE %s OR u.user_login LIKE %s", 
            '%' . $wpdb->esc_like($search) . '%', 
            '%' . $wpdb->esc_like($search) . '%', 
            '%' . $wpdb->esc_like($search) . '%',
            '%' . $wpdb->esc_like($search) . '%'
        );
    }

    // Get the total number of members after applying the search filter
    $total_members = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}slqs_members AS m" . 
    (!empty($search) ? " WHERE m.first_name LIKE '%" . esc_sql($search) . "%' OR m.last_name LIKE '%" . esc_sql($search) . "%' OR u.user_email LIKE '%" . esc_sql($search) . "%' OR u.user_login LIKE '%" . esc_sql($search) . "%'" : ""));

    $total_pages = ceil($total_members / $members_per_page);
    $start = ($page - 1) * $members_per_page;

    // Add LIMIT clause to the base query
    $base_query .= " GROUP BY m.id LIMIT %d, %d";
    $members = $wpdb->get_results($wpdb->prepare($base_query, $start, $members_per_page));

    // Display members
    foreach ($members as $index => $member) {
        $profile_photo = !empty($member->profile_photo) ? esc_url($member->profile_photo) : plugins_url('assets/images/avatar.png', __FILE__);
        ?>
        <div class="col-xl-4 col-sm-6">
            <div class="list-col">
                <div class="cover-img">
                    <img src="https://slqsuae.org/temp/wp-content/uploads/2023/09/FB-2.jpeg" alt="">
                </div>
                <div class="avatar-img">
                    <img src="<?php echo $profile_photo; ?>" alt="" >
                </div>
                <div class="member-name">
                    <a href="<?php echo esc_url(home_url('/member-detail/?member_id=' . $member->id)); ?>"><?php echo esc_html($member->first_name.' '.$member->last_name); ?></a>
                </div>
            </div>
        </div>
        <?php
    }
?>
    <div id="spinner-container">
        <div class="spinner-border text-primary m-1" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <?php
    // Generate pagination 
    echo '<div class="container">';
    echo '<div class="col-lg-12">';
    echo '<ul class="pagination pagination-rounded justify-content-center mt-3 mb-4 pb-1" id="pagination">';

    // Previous button
    if ($page > 1) {
        echo '<li class="page-item" id="prevPage" data-page="' . ($page - 1) . '">
                <a href="javascript:void(0);" class="page-link"><i class="mdi mdi-chevron-left"></i></a>
              </li>';
    } else {
        echo '<li class="page-item disabled" id="prevPage">
                <a href="javascript:void(0);" class="page-link"><i class="mdi mdi-chevron-left"></i></a>
              </li>';
    }

    // Page number links with dots
    if ($total_pages > 5) {
        if ($page < 4) {
            for ($i = 1; $i <= 5; $i++) {
                if ($i == $page) {
                    echo '<li class="page-item active" data-page="' . $i . '"><a href="javascript:void(0);" class="page-link">' . $i . '</a></li>';
                } else {
                    echo '<li class="page-item" data-page="' . $i . '"><a href="javascript:void(0);" class="page-link">' . $i . '</a></li>';
                }
            }
            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
            echo '<li class="page-item" data-page="' . $total_pages . '"><a href="javascript:void(0);" class="page-link">' . $total_pages . '</a></li>';
        } elseif ($page > $total_pages - 3) {
            echo '<li class="page-item" data-page="1"><a href="javascript:void(0);" class="page-link">1</a></li>';
            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
            for ($i = $total_pages - 4; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    echo '<li class="page-item active" data-page="' . $i . '"><a href="javascript:void(0);" class="page-link">' . $i . '</a></li>';
                } else {
                    echo '<li class="page-item" data-page="' . $i . '"><a href="javascript:void(0);" class="page-link">' . $i . '</a></li>';
                }
            }
        } else {
            echo '<li class="page-item" data-page="1"><a href="javascript:void(0);" class="page-link">1</a></li>';
            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
            for ($i = $page - 1; $i <= $page + 1; $i++) {
                if ($i == $page) {
                    echo '<li class="page-item active" data-page="' . $i . '"><a href="javascript:void(0);" class="page-link">' . $i . '</a></li>';
                } else {
                    echo '<li class="page-item" data-page="' . $i . '"><a href="javascript:void(0);" class="page-link">' . $i . '</a></li>';
                }
            }
            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
            echo '<li class="page-item" data-page="' . $total_pages . '"><a href="javascript:void(0);" class="page-link">' . $total_pages . '</a></li>';
        }
    } else {
        // If total pages are 5 or less, show all page numbers
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                echo '<li class="page-item active" data-page="' . $i . '"><a href="javascript:void(0);" class="page-link">' . $i . '</a></li>';
            } else {
                echo '<li class="page-item" data-page="' . $i . '"><a href="javascript:void(0);" class="page-link">' . $i . '</a></li>';
            }
        }
    }

    // Next button
    if ($page < $total_pages) {
        echo '<li class="page-item" id="nextPage" data-page="' . ($page + 1) . '">
                <a href="javascript:void(0);" class="page-link"><i class="mdi mdi-chevron-right"></i></a>
              </li>';
    } else {
        echo '<li class="page-item disabled" id="nextPage">
                <a href="javascript:void(0);" class="page-link"><i class="mdi mdi-chevron-right"></i></a>
              </li>';
    }

    echo '</ul>';
    echo '</div>';
    echo '</div>';
}

add_shortcode('slqs_list_page', 'slqs_list');

function slqs_ajax_load_members() {
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    slqs_display_members($page);
    wp_die(); // This is required to terminate immediately and return a proper response
}

add_action('wp_ajax_load_members', 'slqs_ajax_load_members');
add_action('wp_ajax_nopriv_load_members', 'slqs_ajax_load_members');