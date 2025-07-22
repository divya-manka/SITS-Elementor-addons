<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

if (!defined('ABSPATH'))
    exit;

class sitsel_Popup_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'sitsel_popup_widget';
    }

    public function get_title()
    {
        return esc_html__('Sitsel Popup', 'sitsel');
    }

    public function get_icon()
    {
        return 'eicon-window';
    }

    public function get_categories() {
        return [ 'sits-category' ];
    }

    public function _register_controls()
    {

        // === Button Settings ===
        $this->start_controls_section('sitsel_section_button', [
            'label' => __('Button Settings', 'sitsel'),
        ]);

        $this->add_control('sitsel_button_text', [
            'label' => __('Button Text', 'sitsel'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Open Popup', 'sitsel'),
        ]);

        $this->end_controls_section();

        // === Template Selector ===
        $this->start_controls_section('sitsel_section_popup', [
            'label' => __('Popup Content', 'sitsel'),
        ]);

        $this->add_control('sitsel_template_id', [
            'label' => __('Choose Template', 'sitsel'),
            'type' => Controls_Manager::SELECT,
            'options' => $this->sitsel_get_elementor_templates(),
            'default' => '',
        ]);

        $this->end_controls_section();

        // === Popup Container Style ===
        $this->start_controls_section('sitsel_section_popup_style', [
            'label' => __('Popup Box Style', 'sitsel'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('sitsel_popup_width', [
            'label' => __('Popup Width', 'sitsel'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 300, 'max' => 1000]],
            'default' => ['size' => 600],
            'selectors' => [
                '{{WRAPPER}} .sitsel-popup-container' => 'width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('sitsel_popup_padding', [
            'label' => __('Popup Padding', 'sitsel'),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{WRAPPER}} .sitsel-popup-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'popup_bg',
                'label' => __('Background', 'sitsel'),
                'selector' => '{{WRAPPER}} .sitsel-popup-inner',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'popup_border',
                'label' => __('Border', 'sitsel'),
                'selector' => '{{WRAPPER}} .sitsel-popup-inner',
            ]
        );
        $this->end_controls_section();

        // button style 
        // === Button Style ===
        $this->start_controls_section('sitsel_button_style_section', [
            'label' => __('Button Style', 'sitsel'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        // Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => __('Typography', 'sitsel'),
                'selector' => '{{WRAPPER}} .sitsel-popup-trigger',
            ]
        );

        // Text Color
        $this->add_control('button_text_color', [
            'label' => __('Text Color', 'sitsel'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .sitsel-popup-trigger' => 'color: {{VALUE}};',
            ],
        ]);

        // Background
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_background',
                'label' => __('Background', 'sitsel'),
                'selector' => '{{WRAPPER}} .sitsel-popup-trigger',
            ]
        );

        // Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'label' => __('Border', 'sitsel'),
                'selector' => '{{WRAPPER}} .sitsel-popup-trigger',
            ]
        );

        // Border Radius
        $this->add_control('button_border_radius', [
            'label' => __('Border Radius', 'sitsel'),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{WRAPPER}} .sitsel-popup-trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        // Padding
        $this->add_control('button_padding', [
            'label' => __('Padding', 'sitsel'),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{WRAPPER}} .sitsel-popup-trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $template_id = $settings['sitsel_template_id'];
        ?>
        <div class="sitsel-popup-widget-wrapper">
            <button class="sitsel-popup-trigger"><?php echo esc_html($settings['sitsel_button_text']); ?></button>

            <div class="sitsel-popup-overlay sitsel-hidden">
                <div class="sitsel-popup-container">
                    <div class="sitsel-popup-inner">
                        <span class="sitsel-popup-close">&times;</span>
                        <?php
                        if ($template_id) {
                            echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($template_id);
                        } else {
                            echo '<p>' . esc_html__('No template selected.', 'sitsel') . '</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    private function sitsel_get_elementor_templates()
    {
        $templates = [];

        $args = [
            'post_type' => 'elementor_library',
            'posts_per_page' => -1,
        ];
        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            foreach ($query->posts as $post) {
                $templates[$post->ID] = $post->post_title;
            }
        }

        return $templates;
    }
}
