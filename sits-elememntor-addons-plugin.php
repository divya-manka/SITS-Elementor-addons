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
    function sitsel_enqueue_loop_grid_assets() {
	wp_register_script(
		'sitsel-post-grid',
		plugin_dir_url(__FILE__) . 'assets/js/sitsel-post-grid.js',
		[ 'elementor-frontend' ],
		'1.0',
		true
        );
    }
    add_action( 'elementor/frontend/after_register_scripts', 'sitsel_enqueue_loop_grid_assets' );




    // Register title  widget after Elementor is ready
    add_action( 'elementor/widgets/register', function( $widgets_manager ) {
        require_once plugin_dir_path( __FILE__ ) . 'widgets/title-widget.php';
        $widgets_manager->register( new \SITS_Title_Widget() );
    });

     // Register Loop Grid   widget 
     function sitsel_register_loop_grid_widget( $widgets_manager ) {
	require_once( __DIR__ . '/widgets/sitsel-loop-grid.php' );
	$widgets_manager->register( new \sitsel_Loop_Grid_Widget() );
    }
    add_action( 'elementor/widgets/register', 'sitsel_register_loop_grid_widget' );


}
add_action( 'plugins_loaded', 'sits_elementor_addons_init' );
