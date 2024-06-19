<?php
function kandara_enqueue_styles() {
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
    wp_enqueue_script('kandara-registration-script', get_template_directory_uri() . '/admin.js', array(), null, true);
    wp_enqueue_style('kandara-registration-style', plugin_dir_url(__FILE__) . '../style.css');
}
add_action('wp_enqueue_scripts', 'kandara_enqueue_styles');
