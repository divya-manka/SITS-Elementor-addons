<?php
// widgets/sticky-controls.php

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register sticky enhancer controls.
 */
function sitsel_add_sticky_controls( $element ) {
    $element->start_controls_section(
        'sits_sticky_enhancer_section',
        [
            'label' => esc_html__('SITS Sticky Enhancer', 'sits-elementor-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
        ]
    );

    $element->add_control(
        'sits_enable_sticky_enhancer',
        [
            'label' => esc_html__('Enable SITS Sticky Enhancer', 'sits-elementor-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'sits-elementor-addons'),
            'label_off' => esc_html__('No', 'sits-elementor-addons'),
            'return_value' => 'yes',
            'default' => '',
        ]
    );

    // Enable on Devices
    $element->add_control(
        'sits_enable_on_devices',
        [
            'label' => esc_html__('Enable on Devices', 'sits-elementor-addons'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => [
                'desktop' => esc_html__('Desktop', 'sits-elementor-addons'),
                'tablet' => esc_html__('Tablet', 'sits-elementor-addons'),
                'mobile' => esc_html__('Mobile', 'sits-elementor-addons'),
            ],
            'default' => ['desktop', 'tablet', 'mobile'],
            'condition' => ['sits_enable_sticky_enhancer' => 'yes'],
        ]
    );

    // // Position Type
    // $element->add_control(
    //     'sits_position_type',
    //     [
    //         'label' => esc_html__('Position Type', 'sits-elementor-addons'),
    //         'type' => \Elementor\Controls_Manager::SELECT,
    //         'options' => [
    //             'stick_on_scroll' => esc_html__('Stick on Scroll', 'sits-elementor-addons'),
    //             // 'stick_immediately' => esc_html__('Stick Immediately', 'sits-elementor-addons'),
    //             // 'stick_with_offset' => esc_html__('Stick with Offset', 'sits-elementor-addons'),
    //         ],
    //         'default' => 'stick_on_scroll',
    //         'condition' => ['sits_enable_sticky_enhancer' => 'yes'],
    //     ]
    // );

    // // Sticky Relation
    // $element->add_control(
    //     'sits_sticky_relation',
    //     [
    //         'label' => esc_html__('Sticky Relation', 'sits-elementor-addons'),
    //         'type' => \Elementor\Controls_Manager::SELECT,
    //         'options' => [
    //             'parent' => esc_html__('Parent', 'sits-elementor-addons'),
    //             'viewport' => esc_html__('Viewport', 'sits-elementor-addons'),
    //             'column' => esc_html__('Column', 'sits-elementor-addons'),
    //         ],
    //         'default' => 'parent',
    //         'condition' => ['sits_enable_sticky_enhancer' => 'yes'],
    //     ]
    // );

    // Location
    $element->add_control(
        'sits_location',
        [
            'label' => esc_html__('Location', 'sits-elementor-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'top' => esc_html__('Top', 'sits-elementor-addons'),
                'bottom' => esc_html__('Bottom', 'sits-elementor-addons'),
            ],
            'default' => 'top',
            'condition' => ['sits_enable_sticky_enhancer' => 'yes'],
        ]
    );

    $element->add_control(
        'sits_scroll_offset',
        [
            'label' => esc_html__('Scroll Top Distance', 'sits-elementor-addons'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 500,
            'step' => 10,
            'default' => 100,
            'condition' => [
                'sits_enable_sticky_enhancer' => 'yes',
                'sits_position_type' => ['stick_on_scroll', 'stick_with_offset'],
            ],
        ]
    );

    // $element->add_control(
    //     'replace_on_scroll',
    //     [
    //         'label' => esc_html__('Replace with New Section', 'sits-elementor-addons'),
    //         'type' => \Elementor\Controls_Manager::SWITCHER,
    //         'label_on' => esc_html__('Yes', 'sits-elementor-addons'),
    //         'label_off' => esc_html__('No', 'sits-elementor-addons'),
    //         'return_value' => 'yes',
    //         'default' => '',
    //         'condition' => ['sits_enable_sticky_enhancer' => 'yes'],
    //     ]
    // );

    $element->add_control(
        'sits_sticky_height',
        [
            'label' => esc_html__('Custom Height', 'sits-elementor-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'vh'],
            'range' => [
                'px' => ['min' => 50, 'max' => 200, 'step' => 1],
                'vh' => ['min' => 5, 'max' => 30, 'step' => 1],
            ],
            'default' => ['unit' => 'px', 'size' => 80],
            'condition' => ['sits_enable_sticky_enhancer' => 'yes'],
            'selectors' => [
                '{{WRAPPER}}.sits-sticky-enhanced.is-sticky' => 'height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    // $element->add_control(
    //     'sits_logo_scale',
    //     [
    //         'label' => esc_html__('Logo Scale', 'sits-elementor-addons'),
    //         'type' => \Elementor\Controls_Manager::SLIDER,
    //         'range' => ['px' => ['min' => 0.1, 'max' => 2, 'step' => 0.1]],
    //         'default' => ['size' => 0.8],
    //         'condition' => ['sits_enable_sticky_enhancer' => 'yes'],
    //         'selectors' => [
    //             '{{WRAPPER}}.sits-sticky-enhanced.is-sticky img' => 'transform: scale({{SIZE}});',
    //         ],
    //     ]
    // );

    $element->add_control(
        'sits_transition_duration',
        [
            'label' => esc_html__('Transition Time (ms)', 'sits-elementor-addons'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 100,
            'max' => 2000,
            'step' => 100,
            'default' => 300,
            'condition' => ['sits_enable_sticky_enhancer' => 'yes'],
        ]
    );

    $element->add_control(
        'sits_add_border',
        [
            'label' => esc_html__('Custom Border', 'sits-elementor-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'sits-elementor-addons'),
            'label_off' => esc_html__('No', 'sits-elementor-addons'),
            'return_value' => 'yes',
            'default' => '',
            'condition' => ['sits_enable_sticky_enhancer' => 'yes'],
        ]
    );

    $element->add_control(
        'sits_border_color',
        [
            'label' => esc_html__('Border Color', 'sits-elementor-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#e0e0e0',
            'condition' => [
                'sits_enable_sticky_enhancer' => 'yes',
                'sits_add_border' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}.sits-sticky-enhanced.is-sticky' => 'border-bottom: 1px solid {{VALUE}};',
            ],
        ]
    );

    $element->add_control(
        'sits_add_shadow',
        [
            'label' => esc_html__('Custom Shadow', 'sits-elementor-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'sits-elementor-addons'),
            'label_off' => esc_html__('No', 'sits-elementor-addons'),
            'return_value' => 'yes',
            'default' => '',
            'condition' => ['sits_enable_sticky_enhancer' => 'yes'],
        ]
    );

    $element->add_control(
        'sits_shadow_settings',
        [
            'label' => esc_html__('Shadow', 'sits-elementor-addons'),
            'type' => \Elementor\Controls_Manager::BOX_SHADOW,
            'condition' => [
                'sits_enable_sticky_enhancer' => 'yes',
                'sits_add_shadow' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}.sits-sticky-enhanced.is-sticky' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
            ],
            'default' => [
                'horizontal' => 0,
                'vertical' => 2,
                'blur' => 15,
                'spread' => 0,
                'color' => 'rgba(0, 0, 0, 0.15)',
            ],
        ]
    );

    // Stick on Scroll (Hide on scroll down, show on scroll up)
    $element->add_control(
        'sits_stick_on_scroll',
        [
            'label' => esc_html__('Stick on Scroll', 'sits-elementor-addons'),
            'description' => esc_html__('Hide on scroll down, show on scroll up', 'sits-elementor-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'sits-elementor-addons'),
            'label_off' => esc_html__('No', 'sits-elementor-addons'),
            'return_value' => 'yes',
            'default' => '',
            'condition' => ['sits_enable_sticky_enhancer' => 'yes'],
        ]
    );

    $element->add_control(
        'sits_hide_on_scroll_down',
        [
            'label' => esc_html__('Show on Scrolling Up', 'sits-elementor-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'sits-elementor-addons'),
            'label_off' => esc_html__('No', 'sits-elementor-addons'),
            'return_value' => 'yes',
            'default' => '',
            'condition' => [
                'sits_enable_sticky_enhancer' => 'yes',
                'sits_stick_on_scroll' => 'yes',
            ],
        ]
    );

    $element->add_control(
        'sticky_z_index',
        [
            'label' => esc_html__('Z-Index', 'sits-elementor-addons'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 10,
            'min' => 0,
            'step' => 1,
            'condition' => ['sits_enable_sticky_enhancer' => 'yes'],
            'selectors' => [
                '{{WRAPPER}}.sits-sticky-enhanced.is-sticky' => 'z-index: {{VALUE}};',
            ],
        ]
    );

    $element->end_controls_section();
}

function sitsel_before_render_sticky($element) {
    $settings = $element->get_settings_for_display();
    
    if (isset($settings['sits_enable_sticky_enhancer']) && $settings['sits_enable_sticky_enhancer'] === 'yes') {
        $element->add_render_attribute('_wrapper', 'class', 'sits-sticky-enhanced');
        
        // Add device-specific classes
        $enabledDevices = $settings['sits_enable_on_devices'] ?? ['desktop', 'tablet', 'mobile'];
        if (is_array($enabledDevices)) {
            foreach ($enabledDevices as $device) {
                $element->add_render_attribute('_wrapper', 'class', 'sits-enabled-' . $device);
            }
        }
        
        // Add position type class
        $positionType = $settings['sits_position_type'] ?? 'stick_on_scroll';
        $element->add_render_attribute('_wrapper', 'class', 'sits-position-' . $positionType);
        
        // Add sticky relation class
        $stickyRelation = $settings['sits_sticky_relation'] ?? 'parent';
        $element->add_render_attribute('_wrapper', 'class', 'sits-relation-' . $stickyRelation);
        
        // Add location class
        $location = $settings['sits_location'] ?? 'top';
        $element->add_render_attribute('_wrapper', 'class', 'sits-location-' . $location);
        
        $sticky_data = [
            'enabled_devices' => $enabledDevices,
            'position_type' => $positionType,
            'sticky_relation' => $stickyRelation,
            'location' => $location,
            'stick_on_scroll' => $settings['sits_stick_on_scroll'] ?? '',
            'hide_on_scroll_down' => $settings['sits_hide_on_scroll_down'] ?? '',
            'transition_duration' => $settings['sits_transition_duration'] ?? 300,
            'scroll_offset' => $settings['sits_scroll_offset'] ?? 100,
            'replace_on_scroll' => $settings['replace_on_scroll'] ?? '',
        ];
        
        $element->add_render_attribute('_wrapper', 'data-sits-sticky-settings', json_encode($sticky_data));
    }
}

// Add responsive CSS for device visibility
function sits_add_responsive_css() {
    ?>
    <style>
        /* Desktop visibility */
        @media (min-width: 1025px) {
            .sits-sticky-enhanced:not(.sits-enabled-desktop) {
                position: relative !important;
                top: auto !important;
                left: auto !important;
                right: auto !important;
                z-index: auto !important;
            }
            .sits-sticky-enhanced:not(.sits-enabled-desktop).is-sticky {
                position: relative !important;
                top: auto !important;
                left: auto !important;
                right: auto !important;
                z-index: auto !important;
            }
        }
        
        /* Tablet visibility */
        @media (min-width: 768px) and (max-width: 1024px) {
            .sits-sticky-enhanced:not(.sits-enabled-tablet) {
                position: relative !important;
                top: auto !important;
                left: auto !important;
                right: auto !important;
                z-index: auto !important;
            }
            .sits-sticky-enhanced:not(.sits-enabled-tablet).is-sticky {
                position: relative !important;
                top: auto !important;
                left: auto !important;
                right: auto !important;
                z-index: auto !important;
            }
        }
        
        /* Mobile visibility */
        @media (max-width: 767px) {
            .sits-sticky-enhanced:not(.sits-enabled-mobile) {
                position: relative !important;
                top: auto !important;
                left: auto !important;
                right: auto !important;
                z-index: auto !important;
            }
            .sits-sticky-enhanced:not(.sits-enabled-mobile).is-sticky {
                position: relative !important;
                top: auto !important;
                left: auto !important;
                right: auto !important;
                z-index: auto !important;
            }
        }
    </style>
    <?php
}
add_action('wp_head', 'sits_add_responsive_css');

?>