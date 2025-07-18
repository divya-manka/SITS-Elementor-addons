<?php
/*
Plugin Name: SITS Elementor Addons
Description: A custom Elementor addon that provides a Title Widget.
Version: 1.0.0
Author: Softechure
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// Check if Elementor is loaded after all plugins
function sits_elementor_addons_init() {
    
    
    
    // Show error notice if Elementor is not loaded
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', function() {
            echo '<div class="notice notice-error"><p><strong>SITS Elementor Addons</strong> requires <strong>Elementor</strong> to be installed and activated.</p></div>';
        });
        return;
    }
    
    // Register custom category
    add_action( 'elementor/elements/categories_registered', function( $elements_manager ) {
        $elements_manager->add_category(
            'sits-category',
            [
                'title' => __( 'SITS Widgets', 'sits-elementor-addons' ),
                'icon'  => 'eicon-font',
            ]
        );
    });
    add_action( 'elementor/editor/after_enqueue_scripts', function() {
    wp_enqueue_script(
        'sitsel-post-grid-editor',
        plugin_dir_url(__FILE__) . 'assets/js/sitsel-post-grid.js',
        [ 'jquery' ],
        '1.0.0',
        true
    );
});


    // Register title  widget after Elementor is ready
    add_action( 'elementor/widgets/register', function( $widgets_manager ) {
        require_once plugin_dir_path( __FILE__ ) . 'widgets/title-widget.php';
        $widgets_manager->register( new \SITS_Title_Widget() );
    });

     // Register Loop Grid   widget 
     add_action( 'elementor/widgets/register', function( $widgets_manager ) {
        require_once plugin_dir_path( __FILE__ ) . 'widgets/sitsel-post-grid.php';
        $widgets_manager->register( new \Sitsel_Post_Grid_Widget() );
    });

}
add_action( 'plugins_loaded', 'sits_elementor_addons_init' );
