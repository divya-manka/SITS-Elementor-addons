<?php
/*
Plugin Name: SITS Elementor Addons
Description: A custom Elementor addon that provides a Title Widget.
Version: 1.0.0
Author: Softechure
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Check if Elementor is active
function sits_elementor_addons_init() {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', function() {
            echo '<div class="notice notice-error"><p><strong>SITS Elementor Addons</strong> requires <strong>Elementor</strong> to be installed and activated.</p></div>';
        });
        return;
    }

    // Load widget
    require_once plugin_dir_path( __FILE__ ) . 'widgets/title-widget.php';

    // Register the widget
    add_action( 'elementor/widgets/register', function( $widgets_manager ) {
        require_once plugin_dir_path( __FILE__ ) . 'widgets/title-widget.php';
        $widgets_manager->register( new \SITS_Title_Widget() );
    });
}
add_action( 'plugins_loaded', 'sits_elementor_addons_init' );
