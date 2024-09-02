<?php
function kandara_enqueue_styles() {
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
    wp_enqueue_style('kandara-registration-style', plugin_dir_url(__FILE__) . '../style.css');
    
    wp_enqueue_script('kandara-ajax-script', plugin_dir_url(__FILE__) . '../js/kandara-ajax.js', array('jquery'), null, true);
    // AJAX URL to the script
    wp_localize_script('kandara-ajax-script', 'kandara_ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'kandara_enqueue_styles');
?>