<?php
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class sitsel_loop_slider_widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'sITS_el';
    }

    public function get_title()
    {
        return esc_html__('Sitsel Loop Grid / Slider', 'sitsel');
    }

    public function get_icon()
    {
        return 'eicon-posts-grid';
    }

    public function get_categories()
    {
        return ['sits-category'];
    }

    public function get_keywords()
    {
        return ['grid', 'loop', 'posts', 'custom post type'];
    }

    protected function register_controls()
    {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Query', 'sits-el'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Post Type Selection
        $this->add_control(
            'post_type',
            [
                'label' => esc_html__('Post Type', 'sits-el'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_post_types(),
                'default' => ['post'],
            ]
        );

        // Selection Method: Dynamic or Manual
        $this->add_control(
            'selection',
            [
                'label' => esc_html__('Selection', 'sits-el'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'dynamic' => esc_html__('Dynamic', 'sits-el'),
                    'manual' => esc_html__('Manual', 'sits-el'),
                ],
                'default' => 'dynamic',
            ]
        );

        $this->add_control(
            'manual_posts',
            [
                'label' => esc_html__('Select Posts', 'sits-el'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_all_posts(), // This function should return [ ID => Title ]
                'condition' => [
                    'selection' => 'manual',
                ],
            ]
        );

        // Dynamic: Select Categories
        $this->add_control(
            'categories',
            [
                'label' => esc_html__('Categories', 'sits-el'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_the_category(),
                'condition' => [
                    'selection' => 'dynamic',
                ],
            ]
        );
        // Tags (Dynamic only)
        $this->add_control(
            'manual_tags', // You can rename it to just 'tags' for clarity
            [
                'label' => esc_html__('Filter by Tags', 'sits-el'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_all_tags(),
                'condition' => [
                    'selection' => 'dynamic',
                ],
            ]
        );

        // Offset (Dynamic only)
        $this->add_control(
            'offset',
            [
                'label' => esc_html__('Offset', 'sits-el'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'condition' => [
                    'selection' => 'dynamic',
                ],
            ]
        );

        // Not Found Text (Dynamic only)
        $this->add_control(
            'not_found_text',
            [
                'label' => esc_html__('No Posts Found Text', 'sits-el'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('No posts found.', 'sits-el'),
                'condition' => [
                    'selection' => 'dynamic',
                ],
            ]
        );
        // Posts Per Page
        $this->add_control(
            'posts_per_page',
            [
                'label' => esc_html__('Posts Per Page', 'sits-el'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'default' => 6,
            ]
        );

        // $this->add_control(
        //     'acf_field_key',
        //     [
        //         'label' => __('Select ACF Field', 'plugin-name'),
        //         'type' => \Elementor\Controls_Manager::SELECT,
        //         'options' => $this->get_acf_fields_options(),
        //         'default' => '',
        //     ]
        // );



        $this->end_controls_section();


        // layout tab 
        $this->start_controls_section(
            'layout_section',
            [
                'label' => esc_html__('Layout', 'sits-el'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => esc_html__('Layout', 'sits-el'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => esc_html__('Grid', 'sits-el'),
                    'slider' => esc_html__('Slider', 'sits-el'),
                ],
            ]
        );

        // Gap
        $this->add_control(
            'gap',
            [
                'label' => esc_html__('Gap (px)', 'sitsel'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 30,
                'min' => 0,
                'selectors' => [
                    '{{WRAPPER}} .sitsel-grid' => 'gap: {{VALUE}}px;',

                ],
            ]
        );

        // Columns
        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__('Columns', 'sitsel'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sitsel-grid' => 'display: grid; grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_control(
            'pagination',
            [
                'label' => esc_html__('Show Pagination', 'sitsel'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'sitsel'),
                'label_off' => esc_html__('No', 'sitsel'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'layout' => 'grid',
                ],
            ]
        );
        $this->add_control(
            'navigation',
            [
                'label' => esc_html__('Navigation Type', 'sitsel'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'owl-dots' => esc_html__('Dots', 'sitsel'),
                    'owl-nav' => esc_html__('Arrow', 'sitsel'),
                    'both' => esc_html__('Arrows-Nav', 'sitsel'),
                    'none' => esc_html__('None', 'sitsel'),
                ],
                'condition' => [
                    'layout' => 'slider',
                ],
            ]
        );
        // Autoplay
        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__('Autoplay', 'sitsel'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'sitsel'),
                'label_off' => esc_html__('No', 'sitsel'),
                'return_value' => '1', // returns '1' when ON, '' when OFF
                'default' => '1',
                'condition' => [
                    'layout' => 'slider',
                ],
            ]
        );

        // Autoplay Timeout
        $this->add_control(
            'autoplay_timeout',
            [
                'label' => esc_html__('Autoplay Timeout (ms)', 'sitsel'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3000,
                'condition' => [
                    'layout' => 'slider',
                ],
            ]
        );

        // Loop
        $this->add_control(
            'loop',
            [
                'label' => esc_html__('Loop', 'sitsel'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'sitsel'),
                'label_off' => esc_html__('No', 'sitsel'),
                'return_value' => '1', // returns '1' when ON, '' when OFF
                'default' => '1',
                'condition' => [
                    'layout' => 'slider',
                ],
            ]
        );


        $this->end_controls_section();


        // elements tab
        $this->start_controls_section('section_post_elements', [
            'label' => esc_html__('Elements', 'sitsel'),
        ]);

        $this->add_control(
            'sitsel_feature_image',
            [
                'label' => esc_html__('Show Featured Image', 'sitsel'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'sitsel'),
                'label_off' => esc_html__('Hide', 'sitsel'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $repeater = new Repeater();
        $acf_fields = [];
        $post_type = 'your_post_type'; // Change to your post type or make dynamic

        $sample_post = get_posts([
            'post_type' => $post_type,
            'posts_per_page' => 1,
            'fields' => 'ids',
        ]);

        if (!empty($sample_post)) {
            $post_id = $sample_post[0];
            $custom_fields = get_fields($post_id); // ACF function
            if ($custom_fields && is_array($custom_fields)) {
                foreach ($custom_fields as $key => $value) {
                    $acf_fields[$key] = ucwords(str_replace('_', ' ', $key));
                }
            }
        }

        $element_options = [
            'title' => __('Title', 'sitsel'),
            'content' => __('Content', 'sitsel'),
            'excerpt' => __('Excerpt', 'sitsel'),
            'date' => __('Date', 'sitsel'),
            'time' => __('Time', 'sitsel'),
            'author' => __('Author', 'sitsel'),
            'comments' => __('Comments', 'sitsel'),
            'read_more' => __('Read More', 'sitsel'),
            'category' => __('Category', 'sitsel'),
            'tag' => __('Tags', 'sitsel'),
            // 'featured_image' => __('Featured Image', 'sitsel'),
            'custom_field' => __('Custom Field (Text)', 'sitsel'),
            'acf_image' => __('ACF Image Field', 'sitsel'),
        ];

        if (!empty($acf_fields)) {
            $element_options['acf_fields'] = __('--- ACF Fields ---', 'sitsel');
            foreach ($acf_fields as $key => $label) {
                $element_options[$key] = $label;
            }
        }

        $repeater->add_control('element_type', [
            'label' => esc_html__('Select Element', 'sitsel'),
            'type' => Controls_Manager::SELECT,
            'options' => $element_options,
            'default' => 'title',
        ]);
        $repeater->add_control('custom_field_key', [
            'label' => esc_html__('Custom Field Key', 'sitsel'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => 'e.g., my_custom_field',
            'label_block' => true,
            'condition' => [
                'element_type' => 'custom_field',
            ],
        ]);
        $repeater->add_control('custom_field_key_image', [
            'label' => esc_html__('Field Key (for Custom or ACF)', 'sitsel'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => 'e.g., my_custom_image',
            'label_block' => true,
            'condition' => [
                'element_type' => ['custom_field', 'acf_image'],
            ],
        ]);


        $repeater->add_control('html_tag', [
            'label' => __('HTML Tag (for title)', 'sitsel'),
            'type' => Controls_Manager::SELECT,
            'default' => 'h3',
            'options' => [
                'h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'div' => 'div',
                'span' => 'span',
                'p' => 'p',
            ],
            'condition' => [
                'element_type' => 'title',
            ],
        ]);


        $this->add_control('post_elements', [
            'label' => __('Post Elements', 'sitsel'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'title_field' => '{{ element_type }}',

            'default' => [

                ['element_type' => 'title'],
                ['element_type' => 'date'],
                ['element_type' => 'excerpt'],
            ],
        ]);
        ;

        $this->end_controls_section();



        // STYLE TAB -

        // title styles
        $this->start_controls_section('style_title', [
            'label' => esc_html__('Title', 'sitsel'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('tabs_title_color');

        $this->start_controls_tab('tab_title_normal', [
            'label' => esc_html__('Normal', 'sitsel'),
        ]);

        $this->add_control('title_color', [
            'label' => esc_html__('Text Color', 'sitsel'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .sitsel-post-title a' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('tab_title_hover', [
            'label' => esc_html__('Hover', 'sitsel'),
        ]);

        $this->add_control('title_hover_color', [
            'label' => esc_html__('Text Color', 'sitsel'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .sitsel-post-title a:hover' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // Typography control (optional, not inside tabs)
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .sitsel-post-title a',
            ]
        );

        $this->end_controls_section();

        //  feature image stule tab
        $this->start_controls_section(
            'sitsel_featured_image_style',
            [
                'label' => esc_html__('Featured Image', 'sitsel'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Image Height
        $this->add_responsive_control(
            'sitsel_featured_image_height',
            [
                'label' => esc_html__('Height', 'sitsel'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'selectors' => [
                    '{{WRAPPER}} .sitsel-post-featured-image img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
                ],
            ]
        );

        //image Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'sitsel_featured_image_border',
                'selector' => '{{WRAPPER}} .sitsel-image-overlay-wrap',
            ]
        );

        // image Border Radius
        $this->add_responsive_control(
            'sitsel_featured_image_radius',
            [
                'label' => esc_html__('Border Radius', 'sitsel'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .sitsel-image-overlay-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // image Overlay Animation
        $this->add_control(
            'sitsel_featured_image_overlay_animation',
            [
                'label' => esc_html__('Overlay Animation', 'sitsel'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'fade-in',
                'options' => [
                    'none' => __('None', 'sitsel'),
                    'fade-in' => __('Fade In', 'sitsel'),
                    'fade-out' => __('Fade Out', 'sitsel'),
                    'slide-up' => __('Slide Up', 'sitsel'),
                    'zoom-in' => __('Zoom In', 'sitsel'),
                ],
            ]
        );
        // Overlay Background Color
        $this->add_control(
            'sitsel_overlay_background_color',
            [
                'label' => esc_html__('Overlay Background Color', 'sitsel'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.4)',
                'selectors' => [
                    '{{WRAPPER}} .sitsel-image-overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Overlay Transition Duration
        $this->add_control(
            'sitsel_overlay_transition_duration',
            [
                'label' => esc_html__('Transition Duration (s)', 'sitsel'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.4,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .sitsel-image-overlay' => 'transition-duration: {{SIZE}}s;',
                ],
            ]
        );

        $this->end_controls_section();



        // date style controls
        $this->start_controls_section('style_date_time', [
            'label' => esc_html__('Date Time', 'sitsel'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('date/time_color', [
            'label' => esc_html__('Color', 'sitsel'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .sitsel-post-date' => 'color: {{VALUE}};',
                '{{WRAPPER}} .sitsel-post-time' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'date/time_typography',
                'selector' => '{{WRAPPER}} .sitsel-post-date',
                'selector' => '{{WRAPPER}} .sitsel-post-time',
            ]
        );

        $this->end_controls_section();

        // exceprt style controls
        $this->start_controls_section('style_excerpt', [
            'label' => esc_html__('Excerpt', 'sitsel'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('excerpt_color', [
            'label' => esc_html__('Color', 'sitsel'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .sitsel-post-excerpt' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .sitsel-post-excerpt',
            ]
        );
        $this->end_controls_section();


        // author style controls
        $this->start_controls_section('style_author', [
            'label' => esc_html__('Author', 'sitsel'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('author_color', [
            'label' => esc_html__('Color', 'sitsel'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .sitsel-post-author' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'author_typography',
                'selector' => '{{WRAPPER}} .sitsel-post-author',
            ]
        );
        $this->end_controls_section();


        // category style controls
        $this->start_controls_section('style_category', [
            'label' => esc_html__('category', 'sitsel'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('category_color', [
            'label' => esc_html__('Color', 'sitsel'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .sitsel-post-category a' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'category_typography',
                'selector' => '{{WRAPPER}} .sitsel-post-category a',
            ]
        );
        $this->end_controls_section();


        // comments style tabss
        $this->start_controls_section('style_comments', [
            'label' => esc_html__('Comments', 'sitsel'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('comments_color', [
            'label' => esc_html__('Color', 'sitsel'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .sitsel-post-comments' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'comments_typography',
                'selector' => '{{WRAPPER}} .sitsel-post-comments',
            ]
        );
        $this->end_controls_section();

        // style tabs for custom fields

        $this->start_controls_section(
            'style_custom_field',
            [
                'label' => esc_html__('Custom Field', 'sitsel'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'custom_field_text_color',
            [
                'label' => esc_html__('Text Color', 'sitsel'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sitsel-post-custom-field' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'custom_field_typography',
                'label' => esc_html__('Typography', 'sitsel'),
                'selector' => '{{WRAPPER}} .sitsel-post-custom-field',
            ]
        );

        $this->end_controls_section();

        // read more btn style control
        $this->start_controls_section('style_readmore', [
            'label' => esc_html__('Read More', 'sitsel'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        // Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'readmore_typography',
                'selector' => '{{WRAPPER}} a.sitsel-read-more',
            ]
        );

        // Tabs: Normal / Hover
        $this->start_controls_tabs('tabs_readmore_style');

        $this->start_controls_tab('tab_readmore_style_normal', [
            'label' => esc_html__('Normal', 'sitsel'),
        ]);

        // Text Color
        $this->add_control('readmore_text_color', [
            'label' => esc_html__('Text Color', 'sitsel'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} a.sitsel-read-more' => 'color: {{VALUE}};',
            ],
        ]);

        // Background
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'readmore_bg_normal',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} a.sitsel-read-more',
            ]
        );

        // Border Color
        $this->add_control('readmoretab_border_color', [
            'label' => esc_html__('Border Color', 'sitsel'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} a.sitsel-read-more' => 'border-color: {{VALUE}};',
            ],
        ]);

        // Box Shadow
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'readmore_box_shadow',
                'selector' => '{{WRAPPER}} a.sitsel-read-more',
            ]
        );

        $this->end_controls_tab();

        // Hover Tab
        $this->start_controls_tab('tab_readmore_style_hover', [
            'label' => esc_html__('Hover', 'sitsel'),
        ]);

        // Hover Text Color
        $this->add_control('readmore_text_hover_color', [
            'label' => esc_html__('Text Color', 'sitsel'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} a.sitsel-read-more:hover' => 'color: {{VALUE}};',
            ],
        ]);

        // Hover Background
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'readmore_bg_hover',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} a.sitsel-read-more:hover',
            ]
        );

        // Hover Border Color
        $this->add_control('readmore_border_hover_color', [
            'label' => esc_html__('Border Color', 'sitsel'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} a.sitsel-read-more:hover' => 'border-color: {{VALUE}};',
            ],
        ]);

        // Hover Box Shadow
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'readmore_box_shadow_hover',
                'selector' => '{{WRAPPER}} a.sitsel-read-more:hover',
            ]
        );

        // Transition Duration
        $this->add_control('readmore_transition_duration', [
            'label' => esc_html__('Transition Duration', 'sitsel'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 3,
                    'step' => 0.1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} a.sitsel-read-more' => 'transition-duration: {{SIZE}}s;',
            ],
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();



        // Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'readmore_border',
                'selector' => '{{WRAPPER}} a.sitsel-read-more',
            ]
        );

        // Border Radius
        $this->add_responsive_control('readmore_border_radius', [
            'label' => esc_html__('Border Radius', 'sitsel'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} a.sitsel-read-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        // Margin
        $this->add_responsive_control('readmore_margin', [
            'label' => esc_html__('Margin', 'sitsel'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .sitsel-read-more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        // Padding
        $this->add_responsive_control('readmore_padding', [
            'label' => esc_html__('Padding', 'sitsel'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} a.sitsel-read-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();





        // pagination styles tab

        $this->start_controls_section(
            'style_pagination_section',
            [
                'label' => esc_html__(' Pagination', 'sitsel'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'sitsel_pagination_typography',
                'label' => esc_html__('Typography', 'sitsel'),
                'selector' => '{{WRAPPER}} .sitsel-pagination .page-numbers, {{WRAPPER}} .swiper-button-next:after, {{WRAPPER}} .swiper-button-prev:after',
            ]

        );

        $this->add_control(
            'sitsel_pagination_color',
            [
                'label' => esc_html__('Text Color', 'sitsel'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sitsel-pagination .page-numbers' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .swiper-button-next.swiper-button-disabled, ' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ..swiper-button-prev.swiper-button-disabled' => 'color: {{VALUE}}',
                ],
            ]
        );




        $this->add_control(
            'sitsel_pagination_active_color',
            [
                'label' => esc_html__('Active Color', 'sitsel'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sitsel-pagination .page-numbers.current' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .swiper-button-prev' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .swiper-button-next' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'sitsel_pagination_active_bg',
            [
                'label' => esc_html__('Active Background', 'sitsel'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sitsel-pagination .page-numbers.current' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .swiper-button-prev' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .swiper-button-next' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();



    }

 protected function render()
{
    $settings = $this->get_settings_for_display();

    // Build animation class
    $animation_class = '';
    if (!empty($settings['sitsel_featured_image_overlay_animation']) && $settings['sitsel_featured_image_overlay_animation'] !== 'none') {
        $animation_class = 'sitsel-anim-' . esc_attr($settings['sitsel_featured_image_overlay_animation']);
    }

    $gap = isset($settings['gap']) ? (int)$settings['gap'] : 20;
    $columns = [
        'desktop' => (int)($settings['columns'] ?? 3),
        'tablet' => (int)($settings['columns_tablet'] ?? 2),
        'mobile' => (int)($settings['columns_mobile'] ?? 1),
    ];

    $paged = get_query_var('paged') ? get_query_var('paged') : 1;

    $args = [
        'post_type' => $settings['post_type'],
        'posts_per_page' => $settings['posts_per_page'],
        'paged' => $paged,
    ];

    if ($settings['selection'] === 'manual' && !empty($settings['manual_posts'])) {
        $args['post__in'] = $settings['manual_posts'];
        $args['orderby'] = 'post__in';
    } elseif ($settings['selection'] === 'dynamic') {
        if (!empty($settings['categories'])) {
            $args['category_name'] = implode(',', $settings['categories']);
        }
        if (!empty($settings['manual_tags'])) {
            $args['tag_slug__in'] = $settings['manual_tags'];
        }
        if (!empty($settings['offset'])) {
            $args['offset'] = (int)$settings['offset'];
        }
    }

    $query = new \WP_Query($args);

    if (!$query->have_posts()) {
        echo '<div class="sitsel-not-found">' . esc_html($settings['not_found_text']) . '</div>';
        return;
    }

    $is_slider = ($settings['layout'] === 'slider');
    $wrapper_class = $is_slider ? 'swiper sitsel-slider' : 'sitsel-grid';

    // Open wrapper
    echo '<div class="' . esc_attr($wrapper_class) . '"'
        . ($is_slider ? ' data-gap="' . esc_attr($gap) . '"'
            . ' data-slides-desktop="' . esc_attr($columns['desktop']) . '"'
            . ' data-slides-tablet="' . esc_attr($columns['tablet']) . '"'
            . ' data-slides-mobile="' . esc_attr($columns['mobile']) . '"'
            . ' data-autoplay="' . esc_attr($settings['autoplay']) . '"'
            . ' data-autoplay-timeout="' . esc_attr($settings['autoplay_timeout']) . '"'
            . ' data-loop="' . esc_attr($settings['loop']) . '"' : '')
        . '>';

    if ($is_slider) {
        echo '<div class="swiper-wrapper">';
    }

    while ($query->have_posts()) {
        $query->the_post();

        $item_class = $is_slider ? 'swiper-slide' : 'swiper-grid-item';
        echo '<div class="' . esc_attr($item_class) . '">';
        echo '<div class="' . ($is_slider ? 'sitsel-slide-inner' : 'sitsel-grid-item-inner') . '">';

        // ✅ Featured Image (outside post_elements loop)
        if (!empty($settings['sitsel_feature_image']) && $settings['sitsel_feature_image'] === 'yes') {
            $img_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
            if ($img_url) {
                echo '<div class="sitsel-post-featured-image">';
                echo '<div class="sitsel-image-overlay-wrap ' . $animation_class . '">';
                echo '<a href="' . esc_url(get_permalink()) . '">';
                echo '<img src="' . esc_url($img_url) . '" alt="' . esc_attr(get_the_title()) . '"/>';
                echo '<div class="sitsel-image-overlay"></div>';
                echo '</a>';
                echo '</div>';
                echo '</div>';
            }
        }

        // ✅ Content Wrapper Start
        echo '<div class="sitsel-post-content-wrapper">';

        // ✅ Post Elements Loop
        if (!empty($settings['post_elements'])) {
            foreach ($settings['post_elements'] as $element) {
                $type = $element['element_type'] ?? '';

                switch ($type) {
                    case 'title':
                        $tag = $element['html_tag'] ?? 'h3';
                        echo '<' . esc_attr($tag) . ' class="sitsel-post-title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></' . esc_attr($tag) . '>';
                        break;

                    case 'date':
                        echo '<div class="sitsel-post-date">' . esc_html(get_the_date()) . '</div>';
                        break;

                    case 'time':
                        echo '<div class="sitsel-post-time">' . esc_html(get_the_time()) . '</div>';
                        break;

                    case 'author':
                        echo '<div class="sitsel-post-author">' . esc_html(get_the_author()) . '</div>';
                        break;

                    case 'excerpt':
                        echo '<div class="sitsel-post-excerpt">' . esc_html(get_the_excerpt()) . '</div>';
                        break;

                    case 'content':
                        echo '<div class="sitsel-post-content">' . wp_kses_post(get_the_content()) . '</div>';
                        break;

                    case 'category':
                        $categories = get_the_category();
                        if ($categories) {
                            echo '<div class="sitsel-post-category">';
                            foreach ($categories as $cat) {
                                echo '<span>' . esc_html($cat->name) . '</span> ';
                            }
                            echo '</div>';
                        }
                        break;

                    case 'tag':
                        $tags = get_the_tags();
                        if ($tags) {
                            echo '<div class="sitsel-post-tags">';
                            foreach ($tags as $tag) {
                                echo '<span>' . esc_html($tag->name) . '</span> ';
                            }
                            echo '</div>';
                        }
                        break;

                    case 'comments':
                        echo '<div class="sitsel-post-comments">' . esc_html(get_comments_number()) . ' ' . esc_html__('Comments', 'sitsel') . '</div>';
                        break;

                    case 'read_more':
                        echo '<a href="' . esc_url(get_permalink()) . '" class="sitsel-read-more">' . esc_html__('Read More', 'sitsel') . '</a>';
                        break;

                    case 'custom_field':
                        $key = $element['custom_field_key'] ?? '';
                        if ($key) {
                            $value = get_post_meta(get_the_ID(), $key, true);
                            echo '<div class="sitsel-custom-field">' . esc_html($value) . '</div>';
                        }
                        break;
                }
            }
        }

        echo '</div>'; // ✅ End .sitsel-post-content-wrapper
        echo '</div></div>'; // ✅ End inner + item
    }

    if ($is_slider) {
        echo '</div>'; // .swiper-wrapper

        // Navigation
        if (in_array($settings['navigation'], ['owl-dots', 'both'])) {
            echo '<div class="swiper-pagination"></div>';
        }
        if (in_array($settings['navigation'], ['owl-nav', 'both'])) {
            echo '<div class="swiper-button-next"></div>';
            echo '<div class="swiper-button-prev"></div>';
        }
    }

    echo '</div>'; // .swiper or .grid

    // Pagination for grid layout
    if (!$is_slider && $settings['pagination'] === 'yes') {
        $total_pages = $query->max_num_pages;
        if ($total_pages > 1) {
            echo '<div class="sitsel-pagination">';
            echo paginate_links([
                'total' => $total_pages,
                'current' => $paged,
                'format' => '?paged=%#%',
                'type' => 'list',
                'prev_text' => esc_html__('« Prev', 'sitsel'),
                'next_text' => esc_html__('Next »', 'sitsel'),
            ]);
            echo '</div>';
        }
    }

    wp_reset_postdata();
}




    private function get_acf_fields_options()
    {
        $options = [];

        if (!function_exists('acf_get_field_groups')) {
            return ['' => 'ACF not active'];
        }

        // Adjust post type if needed
        $field_groups = acf_get_field_groups(['post_type' => 'post']);

        if (!$field_groups) {
            return ['' => 'No ACF Field Groups Found'];
        }

        foreach ($field_groups as $group) {
            $fields = acf_get_fields($group['key']);

            if (!$fields)
                continue;

            foreach ($fields as $field) {
                $options[$field['name']] = $field['label'] . ' (' . $field['name'] . ')';
            }
        }

        return !empty($options) ? $options : ['' => 'No ACF fields found'];
    }



    // functions for get post type and categories
    private function get_post_types()
    {
        $post_types = get_post_types(['public' => true], 'objects');
        $options = [];

        foreach ($post_types as $post_type) {
            $options[$post_type->name] = $post_type->label;
        }

        return $options;
    }

    // category function
    private function get_the_category()
    {
        $categories = get_categories(['hide_empty' => false]);
        $options = [];
        foreach ($categories as $category) {
            $options[$category->slug] = $category->name;
        }
        return $options;
    }


    // get all posts 
    private function get_all_posts()
    {
        $posts = get_posts(['numberposts' => -1, 'post_type' => 'any']);
        $options = [];
        foreach ($posts as $post) {
            $options[$post->ID] = $post->post_title;
        }
        return $options;
    }

    // get all tags
    private function get_all_tags()
    {
        $tags = get_tags(['hide_empty' => false]);
        $options = [];
        foreach ($tags as $tag) {
            $options[$tag->slug] = $tag->name;
        }
        return $options;
    }




}