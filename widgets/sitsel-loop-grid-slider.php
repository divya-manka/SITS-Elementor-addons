<?php
use Elementor\Controls_Manager;
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
        return esc_html__('SITSel Loop Grid / Slider', 'sitsel');
    }

    public function get_icon()
    {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return [ 'sits-category' ];
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

        $this->add_control(
            'acf_field_key',
            [
                'label' => __('Select ACF Field', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_acf_fields_options(),
                'default' => '',
            ]
        );



        $this->end_controls_section();

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

        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Style', 'sitsel'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_style_heading',
            [
                'label' => esc_html__('Post Title', 'sitsel'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'sitsel'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sitsel-post-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .sitsel-post-title',
            ]
        );

        // thumbnail image style
        $this->add_control(
            'image_style_heading',
            [
                'label' => esc_html__('Post Thumbnail', 'sitsel'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .sitsel-post-thumbnail img',
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'sitsel'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .sitsel-post-thumbnail img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // expert style
        $this->add_control(
            'excerpt_style_heading',
            [
                'label' => esc_html__('Excerpt', 'sitsel'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label' => esc_html__('Color', 'sitsel'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sitsel-post-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .sitsel-post-excerpt',
            ]
        );

        // read more btn
        $this->add_control(
            'read_more_style_heading',
            [
                'label' => esc_html__('Read More Button', 'sitsel'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'read_more_color',
            [
                'label' => esc_html__('Text Color', 'sitsel'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sitsel-read-more a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'read_more_background',
            [
                'label' => esc_html__('Background Color', 'sitsel'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sitsel-read-more' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'read_more_typography',
                'selector' => '{{WRAPPER}} .sitsel-read-more a',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'read_more_border',
                'selector' => '{{WRAPPER}} .sitsel-read-more ',
            ]
        );

        $this->add_control(
            'read_more_border_radius',
            [
                'label' => esc_html__('Border Radius', 'sitsel'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .sitsel-read-more ' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'read_more_padding',
            [
                'label' => esc_html__('Padding', 'sitsel'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .sitsel-read-more ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );



        $this->end_controls_section();


    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $gap = isset($settings['gap']) ? (int) $settings['gap'] : 20;
        // $columns = isset($settings['columns']) ? (int) $settings['columns'] : 3;
        $columns = [
            'desktop' => isset($settings['columns']) ? (int) $settings['columns'] : 3,
            'tablet' => isset($settings['columns_tablet']) ? (int) $settings['columns_tablet'] : 2,
            'mobile' => isset($settings['columns_mobile']) ? (int) $settings['columns_mobile'] : 1,
        ];

        $acf_key = isset($settings['acf_field_key']) ? $settings['acf_field_key'] : '';

        // if (empty($acf_key)) {
        //     echo 'No ACF field selected';
        //     return;
        // }



        $paged = get_query_var('paged') ? get_query_var('paged') : 1;

        $args = [
            'post_type' => $settings['post_type'],
            'posts_per_page' => $settings['posts_per_page'],
            'paged' => $paged,
        ];

        if ($settings['selection'] === 'manual' && !empty($settings['manual_posts'])) {
            $args['post__in'] = $settings['manual_posts'];
            $args['orderby'] = 'post__in';
        }

        if ($settings['selection'] === 'dynamic') {
            if (!empty($settings['categories'])) {
                $args['category_name'] = implode(',', $settings['categories']);
            }
            if (!empty($settings['manual_tags'])) {
                $args['tag_slug__in'] = $settings['manual_tags'];
            }
            if (!empty($settings['offset'])) {
                $args['offset'] = (int) $settings['offset'];
            }
        }

        $query = new \WP_Query($args);



        if (!$query->have_posts()) {
            echo '<div class="sitsel-not-found">' . esc_html($settings['not_found_text']) . '</div>';
            return;
        }

        $is_slider = ($settings['layout'] === 'slider');

        // Start wrapper


        if ($is_slider) {
            echo '<div class="swiper sitsel-slider" 
                data-gap="' . esc_attr($gap) . '" 
                 data-slides-desktop="' . esc_attr($columns['desktop']) . '" 
                data-slides-tablet="' . esc_attr($columns['tablet']) . '" 
                data-slides-mobile="' . esc_attr($columns['mobile']) . '" 
                data-autoplay="' . esc_attr($settings['autoplay']) . '" 
                data-autoplay-timeout="' . esc_attr($settings['autoplay_timeout']) . '" 
                data-loop="' . esc_attr($settings['loop']) . '">';


            echo '<div class="swiper-wrapper">';

            while ($query->have_posts()) {
                $query->the_post();
                $title = get_the_title();
                $img_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                $excerpt = get_the_excerpt();
                $read_more_text = __('Read More', 'sitsel');

                $field_key = $settings['acf_field_key'];
                $value = get_field($field_key, get_the_ID());
                if (is_array($value)) {
                    $value = implode(', ', $value);
                }

                echo '<div class="swiper-slide">';
                echo '<div class="sitsel-slide-inner">';
                if ($img_url) {
                    echo '<a href="' . esc_url(get_the_permalink()) . '"><img src="' . esc_url($img_url) . '" alt="' . esc_attr($title) . '" /></a>';
                }
                echo '<h3 class="sitsel-post-title"><a href="' . esc_url(get_the_permalink()) . '">' . esc_html($title) . '</a></h3>';

                $value = get_field($acf_key);

                if ($value) {
                    echo '<div class="sitsel-acf-fields-value">' . esc_html($value) . '</div>';
                }

                echo '<div class="sitsel-post-excerpt">' . esc_html($excerpt) . '</div>';
                echo '<div class="sitsel-read-more"><a href="' . esc_url(get_the_permalink()) . '">' . esc_html($read_more_text) . '</a></div>';
                echo '</div></div>';
            }


            echo '</div>'; // .swiper-wrapper
            // Conditionally output navigation/pagination
            if ($settings['navigation'] === 'owl-dots') {
                echo '<div class="swiper-pagination"></div>';
            }

            if ($settings['navigation'] === 'owl-nav') {
                echo '<div class="swiper-button-next"></div>';
                echo '<div class="swiper-button-prev"></div>';
            }
            if ($settings['navigation'] === 'both') {
                echo '<div class="swiper-pagination"></div>';
                echo '<div class="swiper-button-next"></div>';
                echo '<div class="swiper-button-prev"></div>';
            }

            echo '</div>'; // .swiper
        } else {
            echo '<div class="sitsel-grid" >';
            while ($query->have_posts()) {
                $query->the_post();
                $title = get_the_title();
                $img_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                $excerpt = get_the_excerpt();
                $read_more_text = __('Read More', 'sitsel');

                $field_key = $settings['acf_field_key'];
                $value = get_field($field_key, get_the_ID());
                if (is_array($value)) {
                    $value = implode(', ', $value);
                }

                echo '<div class="swiper-slide">';
                echo '<div class="sitsel-slide-inner">';
                if ($img_url) {
                    echo '<a href="' . esc_url(get_the_permalink()) . '"><img src="' . esc_url($img_url) . '" alt="' . esc_attr($title) . '" /></a>';
                }
                echo '<h3 class="sitsel-post-title"><a href="' . esc_url(get_the_permalink()) . '">' . esc_html($title) . '</a></h3>';
                $value = get_field($acf_key);

                if ($value) {
                    echo '<div class="sitsel-acf-fields-value">' . esc_html($value) . '</div>';
                }
                echo '<div class="sitsel-post-excerpt">' . esc_html($excerpt) . '</div>';
                echo '<div class="sitsel-read-more"><a href="' . esc_url(get_the_permalink()) . '">' . esc_html($read_more_text) . '</a></div>';
                echo '</div></div>';
            }
            echo '</div>';
            if ($settings['layout'] === 'grid' && $settings['pagination'] === 'yes') {
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