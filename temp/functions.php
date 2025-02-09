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

function enqueue_vapi_cdn_script() {
    wp_enqueue_script(
        'vapi-js',
        'https://cdn.jsdelivr.net/npm/@vapi-ai/web@latest/dist/vapi.min.js',
        array(),
        null,
        true
    );
}
add_action('wp_enqueue_scripts', 'enqueue_vapi_cdn_script');

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
