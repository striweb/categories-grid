<?php
/*
Plugin Name: Categories Grid
Description: The Categories Grid plugin allows you to display your WooCommerce product categories in a fully customizable grid. This versatile plugin enables you to adjust various visual settings to create a tailored and visually appealing display of your product categories. Shortcode: [main_categories]
Version: 1.3
Author: Simeon Bakalov
*/
 
// Define constants
define('CATEGORIES_GRID_VERSION', '1.3');
define('CATEGORIES_GRID_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CATEGORIES_GRID_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once CATEGORIES_GRID_PLUGIN_DIR . 'includes/settings.php';

// Enqueue plugin styles
function categories_grid_enqueue_styles() {
    wp_enqueue_style('categories-grid-css', CATEGORIES_GRID_PLUGIN_URL . 'assets/css/categories-grid.css', array(), CATEGORIES_GRID_VERSION);
}
add_action('wp_enqueue_scripts', 'categories_grid_enqueue_styles');

// Function to display all main categories in a grid
function display_main_categories() {
    // Get settings
    $hide_uncategorized = get_option('hide_uncategorized', false);
    $number_of_columns = get_option('number_of_columns', 3);
    $grid_gap = get_option('grid_gap', 20);
    $border_radius = get_option('border_radius', 8);
    $enable_shadows = get_option('enable_shadows', true);
    $background_color = get_option('background_color', '#fff');
    $text_color = get_option('text_color', '#333');
    $item_padding = get_option('item_padding', 20);
    $font_size = get_option('font_size', 16);
    $container_width = get_option('container_width', 100);
    $hidden_categories = get_option('hidden_categories', []);
    $grid_border_color = get_option('grid_border_color', '#ddd');
    $hover_background_color = get_option('hover_background_color', '#f7f7f7');
    $hover_text_color = get_option('hover_text_color', '#000');
    $show_category_description = get_option('show_category_description', false);
    $custom_css = get_option('custom_css', '');

    // Get product categories
    $args = array(
        'taxonomy'   => 'product_cat',
        'parent'     => 0,
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => false,
    );
    $product_categories = get_terms($args);

    // Load the template
    ob_start();
    include CATEGORIES_GRID_PLUGIN_DIR . 'templates/grid.php';
    return ob_get_clean();
}

// Register shortcode
function register_main_categories_shortcode() {
    add_shortcode('main_categories', 'display_main_categories');
}
add_action('init', 'register_main_categories_shortcode');

// Add Settings link on the plugins page
function categories_grid_plugin_action_links($links) {
    $settings_link = '<a href="options-general.php?page=categories-grid-settings">' . __('Settings') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'categories_grid_plugin_action_links');
?>
