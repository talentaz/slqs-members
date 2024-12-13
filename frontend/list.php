<?php
// add_action('wp_enqueue_scripts', 'slqs_enqueue_list_scripts');
// function slqs_enqueue_list_scripts() {
//     slqs_enqueue_scripts_and_styles();
// }


function slqs_list() {
    ob_start();
    ?>
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

function slqs_display_members($page) {
    // Example data
    $members_per_page = 12;
    $total_members = 60; // Example total members
    $total_pages = ceil($total_members / $members_per_page);
    $start = ($page - 1) * $members_per_page;

    // Display members
    for ($i = $start; $i < min($start + $members_per_page, $total_members); $i++) {
        ?>
        <div class="col-xl-4 col-sm-6">
            <div class="list-col">
                <div class="cover-img">
                    <img src="http://localhost/wordpress/wp-content/uploads/2023/09/FB-2.jpeg" alt="">
                </div>
                <div class="avatar-img">
                    <img src="http://localhost/wordpress/wp-content/uploads/2023/09/0528382584-150x150.jpeg" alt="">
                </div>
                <div class="member-name">
                    <a href="#">Member <?php echo $i + 1; ?></a>
                </div>
            </div>
        </div>
        <?php
    }

    // Generate pagination
    echo '<div class="row">';
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

    // Page number links
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $page) {
            echo '<li class="page-item active" data-page="' . $i . '"><a href="javascript:void(0);" class="page-link">' . $i . '</a></li>';
        } else {
            echo '<li class="page-item" data-page="' . $i . '"><a href="javascript:void(0);" class="page-link">' . $i . '</a></li>';
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