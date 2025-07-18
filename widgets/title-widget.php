<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class SITS_Title_Widget extends Widget_Base {

    public function get_name() {
        return 'sits_title';
    }

    public function get_title() {
        return __( 'SITS Title', 'sits-elementor-addons' );
    }

    public function get_icon() {
        return 'eicon-t-letter';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'sits-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title_text',
            [
                'label' => __( 'Title Text', 'sits-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Hello from SITS!', 'sits-elementor-addons' ),
                'placeholder' => __( 'Enter your title', 'sits-elementor-addons' ),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        echo '<h2>' . esc_html( $settings['title_text'] ) . '</h2>';
    }
}
