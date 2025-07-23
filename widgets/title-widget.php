<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;

class SITS_Title_Widget extends Widget_Base {

    public function get_name() {
        return 'sits_title';
    }

    public function get_title() {
        return __( 'Sitsel Title', 'sits-elementor-addons' );
    }

    public function get_icon() {
        return 'eicon-t-letter';
    }

    public function get_categories() {
        return [ 'sits-category' ];
    }

    protected function register_controls() {

        // Content Controls
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Heading', 'sits-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'sits-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Add Your Heading Text Here', 'sits-elementor-addons' ),
                'placeholder' => __( 'Enter your title', 'sits-elementor-addons' ),
                'label_block' => true,
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __( 'Link', 'sits-elementor-addons' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'sits-elementor-addons' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'html_tag',
            [
                'label' => __( 'HTML Tag', 'sits-elementor-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Controls
        $this->start_controls_section(
            'style_section',
            [
                'label' => __( 'Heading', 'sits-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => __( 'Alignment', 'sits-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'sits-elementor-addons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'sits-elementor-addons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'sits-elementor-addons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __( 'Justify', 'sits-elementor-addons' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .sitsel-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .sitsel-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'text_stroke',
                'selector' => '{{WRAPPER}} .sitsel-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .sitsel-title',
            ]
        );

        $this->add_control(
            'blend_mode',
            [
                'label' => __( 'Blend Mode', 'sits-elementor-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'normal',
                'options' => [
                    'normal' => 'Normal',
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'color-burn' => 'Color Burn',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sitsel-title' => 'mix-blend-mode: {{VALUE}}',
                ],
            ]
        );

        $this->start_controls_tabs( 'text_color_tabs' );

        $this->start_controls_tab(
            'normal_text_color',
            [
                'label' => __( 'Normal', 'sits-elementor-addons' ),
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'sits-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sitsel-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'hover_text_color',
            [
                'label' => __( 'Hover', 'sits-elementor-addons' ),
            ]
        );

        $this->add_control(
            'sits_hover_text_color',
            [
                'label' => __( 'Hover Color', 'sits-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sitsel-title:hover' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $tag = $settings['html_tag'] ? $settings['html_tag'] : 'h2';

        $this->add_render_attribute( 'title_attr', 'class', 'sitsel-title' );

        if ( ! empty( $settings['link']['url'] ) ) {
            $this->add_render_attribute( 'link_attr', 'href', esc_url( $settings['link']['url'] ) );
            if ( $settings['link']['is_external'] ) {
                $this->add_render_attribute( 'link_attr', 'target', '_blank' );
            }
            if ( $settings['link']['nofollow'] ) {
                $this->add_render_attribute( 'link_attr', 'rel', 'nofollow' );
            }

            echo sprintf(
                '<%1$s %2$s><a %3$s>%4$s</a></%1$s>',
                esc_html( $tag ),
                $this->get_render_attribute_string( 'title_attr' ),
                $this->get_render_attribute_string( 'link_attr' ),
                esc_html( $settings['title'] )
            );
        } else {
            echo sprintf(
                '<%1$s %2$s>%3$s</%1$s>',
                esc_html( $tag ),
                $this->get_render_attribute_string( 'title_attr' ),
                esc_html( $settings['title'] )
            );
        }
    }
}
