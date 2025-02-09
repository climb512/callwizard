<?php
// Enqueue parent theme stylesheet
function my_theme_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_uri(), array('parent-style'));
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');

function add_chartjs_script() {
    wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'add_chartjs_script');

/***************************
 * Vapi related
 */
function enqueue_local_vapi_script() {
    wp_enqueue_script(
        'vapi-js', // Handle for the script
        get_stylesheet_directory_uri() . '/js/vapi.bundle.js', // Path to the file
        array(), // Dependencies (add here if required)
        '1.0.0', // Version (optional)
        true // Load in the footer
    );
}
add_action('wp_enqueue_scripts', 'enqueue_local_vapi_script');

function enqueue_dashboard_scripts() {
    wp_enqueue_script('dashboard-script', get_stylesheet_directory_uri() . '/js/dashboard.js', array('jquery'), null, true);

    wp_localize_script('dashboard-script', 'ajax_object', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_dashboard_scripts');

/***************************
 *   Affiliate related
 */
// Add a cookie to preserve the affiliate link information
function preserve_ref_parameter() {
    if (isset($_GET['ref'])) {
        // Add the ref parameter as a cookie for persistence
        setcookie('ref', sanitize_text_field($_GET['ref']), time() + 3600, "/");
    }
}
add_action('init', 'preserve_ref_parameter');

// Append the ref parameter to the confirmation page URL
function append_ref_to_confirmation_page() {
    if (isset($_COOKIE['ref']) && is_page('membership-confirmation')) {
        $ref = sanitize_text_field($_COOKIE['ref']);
        if (!isset($_GET['ref'])) {
            // Append the ref parameter to the URL
            wp_redirect(add_query_arg('ref', $ref, home_url($_SERVER['REQUEST_URI'])));
            exit;
        }
    }
}
add_action('template_redirect', 'append_ref_to_confirmation_page');
//**************************

function get_user_assistants() {
    // Check if the user is logged in
    if (!is_user_logged_in()) {
        return [];
    }

    $user_id = get_current_user_id();
    global $wpdb;

    // Use get_col to retrieve a single column of data as an array
    $assistant_ids = $wpdb->get_col("
        SELECT meta_value FROM pss_postmeta
        WHERE meta_key = 'pss_assistant_id_cpt__assistant_id'
        AND post_id IN (
            SELECT meta_value FROM pss_postmeta
            WHERE meta_key = 'pss_organization_cpt__assistant_ids'
            AND post_id = (
                SELECT post_id FROM pss_postmeta
                WHERE meta_key = 'pss_organization_cpt__user_id'
                AND meta_value = '" . esc_sql($user_id) . "'
            )
        )
    ");

    return $assistant_ids;
}

// Create a shortcode to output the assistant_ids as a JSON object (poplate the dashboard dropdown)
function assistant_dropdown_data_shortcode() {
    $assistants = get_user_assistants();
    return '<script>var assistantData = ' . json_encode($assistants) . ';</script>';
}
add_shortcode('assistant_dropdown_data', 'assistant_dropdown_data_shortcode');


/**
 * Save user selected Assistant_id data in user_meta
 */
add_action('wp_ajax_save_assistant_id', 'save_assistant_id');
function save_assistant_id() {
    // Check if the user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in');
    }

    // Validate input
    if (!isset($_POST['assistant_id'])) {
        wp_send_json_error('No assistant ID provided');
    }

    $assistant_id = sanitize_text_field($_POST['assistant_id']);
    $user_id = get_current_user_id();

    // Save the assistant ID to user_meta
    update_user_meta($user_id, 'selected_assistant_id', $assistant_id);

    wp_send_json_success('Assistant ID saved successfully');
}

/**
 * DEBUG
 */
// add_action('wp_footer', function () {
//     global $wp_scripts;
//     error_log(get_stylesheet_directory_uri() . '/js/phptojs.js');
//     echo '<pre>';
//     echo "Enqueued Scripts:\n";
//     foreach ($wp_scripts->queue as $script) {
//         echo $script . ' - ' . $wp_scripts->registered[$script]->src . "\n";
//     }
//     echo '</pre>';
// });
