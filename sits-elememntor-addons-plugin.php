<?php
/*
Plugin Name: SITS Elementor Addons
Description: A custom Elementor addon that provides Widgets.
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


    // Register Elementor Widgets
    add_action( 'elementor/widgets/register', function( $widgets_manager ) {
        require_once __DIR__ . '/widgets/title-widget.php';
        require_once __DIR__ . '/widgets/sitsel-loop-grid-slider.php';
        require_once __DIR__ . '/widgets/sitsel-popup-widget.php';
        require_once(__DIR__ . '/widgets/sitsel-loop-grid.php');  
        require_once(__DIR__ . '/widgets/sitsel_testimonial_widget.php');  

        $widgets_manager->register( new \SITS_Title_Widget() );
        $widgets_manager->register( new \sitsel_Loop_Grid_Widget() );
        $widgets_manager->register( new \sitsel_Popup_Widget() );
        $widgets_manager->register( new \sitsel_Popup_Widget() );
        $widgets_manager->register(new \sitsel_loop_slider_widget());
        $widgets_manager->register(new \sitsel_testimonial_widget());
    });

    add_action( 'elementor/frontend/after_enqueue_scripts', function() {
        wp_enqueue_script( 'sitsel-popup-js', plugin_dir_url( __FILE__ ) . 'assets/js/sitsel-popup.js', [ 'jquery' ], '1.0', true );
        wp_enqueue_script( 'sitsel-post-grid.js', plugin_dir_url( __FILE__ ) . 'assets/js/sitsel-post-grid.js', [ 'jquery' ], '1.0', true );
        wp_enqueue_style( 'sitsel-popup-css', plugin_dir_url( __FILE__ ) . 'assets/css/sitsel-popup.css' );
        wp_enqueue_style( 'sitsel-comman.css', plugin_dir_url( __FILE__ ) . 'assets/css/sitsel-comman.css' );
   });


    add_action('wp_enqueue_scripts', function () {
        wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css');
        wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', [], false, true);
    });


}
add_action( 'plugins_loaded', 'sits_elementor_addons_init' );
