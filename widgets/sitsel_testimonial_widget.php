<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if (!defined('ABSPATH'))
    exit;

class sitsel_testimonial_widget extends Widget_Base
{

    public function get_name()
    {
        return 'sitsel_testimonial';
    }

    public function get_title()
    {
        return esc_html__('Sitsel Testimonial', 'sitsel');
    }

    public function get_icon()
    {
        return 'eicon-testimonial';
    }

   public function get_categories() {
        return [ 'sits-category' ];
    }

    public function _register_controls()
    {
        // Layout Type
        $this->start_controls_section('content_section', [
            'label' => esc_html__('Testimonials Content', 'sitsel'),
        ]);

        $this->add_control('source_type', [
            'label' => esc_html__('Content Source', 'sitsel'),
            'type' => Controls_Manager::SELECT,
            'default' => 'manual',
            'options' => [
                'manual' => esc_html__('Manual Input', 'sitsel'),
                'dynamic' => esc_html__('Dynamic', 'sitsel'),
            ],
        ]);

        // Manual Testimonials
        $repeater = new Repeater();

        $repeater->add_control('author', [
            'label' => esc_html__('Author', 'sitsel'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => ['active' => true],
            'default' => 'John Doe',
        ]);

        $repeater->add_control('job', [
            'label' => esc_html__('Job', 'sitsel'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => ['active' => true],
            'default' => 'Sony CEO',
        ]);

        $repeater->add_control('author_image', [
            'label' => esc_html__('Author Image', 'sitsel'),
            'type' => Controls_Manager::MEDIA,
            'dynamic' => ['active' => true],
            'default' => [
                'url' => Utils::get_placeholder_image_src(),
            ],
        ]);

        $repeater->add_control('company_logo', [
            'label' => esc_html__('Company Logo', 'sitsel'),
            'type' => Controls_Manager::MEDIA,
        ]);

        $repeater->add_control('title', [
            'label' => esc_html__('Title', 'sitsel'),
            'type' => Controls_Manager::TEXT,
            'default' => 'Awesome Theme',
        ]);

        $repeater->add_control('rating', [
            'label' => esc_html__('Rating', 'sitsel'),
            'type' => Controls_Manager::NUMBER,
            'default' => 4.5,
            'min' => 0,
            'max' => 5,
            'step' => 0.1,
        ]);

        $repeater->add_control('content', [
            'label' => esc_html__('Testimonial', 'sitsel'),
            'type' => Controls_Manager::WYSIWYG,
            'dynamic' => ['active' => true],
            'default' => 'Lorem ipsum dolor sit amet...',
        ]);

        $repeater->add_control('image', [ // Optional: keep this or replace with author_image
            'label' => esc_html__('Image', 'sitsel'),
            'type' => Controls_Manager::MEDIA,
            'default' => [
                'url' => Utils::get_placeholder_image_src(),
            ],
        ]);


        $this->add_control('testimonials', [
            'label' => esc_html__('Testimonials', 'sitsel'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'condition' => ['source_type' => 'manual'],
            'default' => [
                [
                    'author_name' => 'Alice Smith',
                    'author_job' => 'Marketing Manager',
                    'testimonial_content' => 'Amazing experience and support.',
                    'author_image' => ['url' => Utils::get_placeholder_image_src()],
                ],
                [
                    'author_name' => 'Bob Johnson',
                    'author_job' => 'Product Designer',
                    'testimonial_content' => 'Helped us scale quickly and easily.',
                    'author_image' => ['url' => Utils::get_placeholder_image_src()],
                ],
                [
                    'author_name' => 'Carol White',
                    'author_job' => 'Developer Advocate',
                    'testimonial_content' => 'The best platform I’ve used in years.',
                    'author_image' => ['url' => Utils::get_placeholder_image_src()],
                ],
            ],
        ]);

        $this->add_control('dynamic_post_type', [
    'label' => esc_html__('Post Type', 'sitsel'),
    'type' => Controls_Manager::SELECT,
    'options' => get_post_types(['public' => true], 'names'),
    'default' => 'testimonial',
    'condition' => ['source_type' => 'dynamic'],
]);

$this->add_control('posts_per_page', [
    'label' => esc_html__('Number of Posts', 'sitsel'),
    'type' => Controls_Manager::NUMBER,
    'default' => 5,
    'condition' => ['source_type' => 'dynamic'],
]);

$this->add_control('template_id', [
    'label' => esc_html__('Select Layout Template', 'sitsel'),
    'type' => Controls_Manager::SELECT2,
    'label_block' => true,
    'options' => $this->get_elementor_templates(), // you'll define this function
    'condition' => ['source_type' => 'dynamic'],
]);


        $this->end_controls_section();

        // Swiper Settings
        $this->start_controls_section('swiper_settings', [
            'label' => esc_html__('Slider Settings', 'sitsel'),
        ]);

        $this->add_control('autoplay', [
            'label' => esc_html__('Autoplay', 'sitsel'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'false',
        ]);

        $this->add_control('speed', [
            'label' => esc_html__('Autoplay Speed (ms)', 'sitsel'),
            'type' => Controls_Manager::NUMBER,
            'default' => 3000,
            'condition' => ['autoplay' => 'yes'],
        ]);
        $this->add_control('image_size', [
            'label' => esc_html__('Image Resolution', 'sitsel'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'thumbnail' => 'Thumbnail',
                'medium' => 'Medium',
                'large' => 'Large',
                'full' => 'Full',
            ],
            'default' => 'full',
        ]);

       $this->add_responsive_control('columns', [
            'label' => esc_html__('Columns', 'sitsel'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
            'default' => '3',
            'tablet_default' => '2',
            'mobile_default' => '1',
            'selectors' => [],
        ]);

       

        $this->add_control('slides_to_scroll', [
            'label' => esc_html__('Slides to Scroll', 'sitsel'),
            'type' => Controls_Manager::NUMBER,
            'default' => 1,
            'min' => 1,
        ]);

        $this->add_control('gutter', [
            'label' => esc_html__('Gutter', 'sitsel'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => ['min' => 0, 'max' => 100],
            ],
            'default' => ['size' => 15],
        ]);

        $this->add_control('navigation', [
            'label' => esc_html__('Navigation', 'sitsel'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        // $this->add_control('show_on_hover', [
//     'label' => esc_html__('Show on Hover', 'sitsel'),
//     'type' => Controls_Manager::SWITCHER,
// ]);

        // $this->add_control('nav_icon', [
//     'label' => esc_html__('Select Icon', 'sitsel'),
//     'type' => Controls_Manager::SELECT,
//     'options' => [
//         'angle' => 'Angle',
//         'chevron' => 'Chevron',
//         'arrow' => 'Arrow',
//     ],
//     'default' => 'angle',
//     'condition' => ['navigation' => 'yes'],
// ]);

        $this->add_control('pagination', [
            'label' => esc_html__('Pagination', 'sitsel'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'no',
        ]);

        $this->add_control('loop', [
            'label' => esc_html__('Infinite Loop', 'sitsel'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('effect', [
            'label' => esc_html__('Effect', 'sitsel'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'slide' => 'Slide',
                'fade' => 'Fade',
            ],
            'default' => 'slide',
        ]);

        $this->add_control('transition_speed', [
            'label' => esc_html__('Transition Duration (sec)', 'sitsel'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0.7,
            'step' => 0.1,
        ]);

        $this->add_control(
            'quote_icon',
            [
                'label' => __('Select Quote Icon', 'sitsel'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'default' => [
                    'value' => 'fas fa-quote-left',
                    'library' => 'fa-solid',
                ],
                 'condition' => ['source_type' => 'manual'],
            ]
        );





        $this->add_control('rating', [
            'label' => esc_html__('Rating', 'sitsel'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
             'condition' => ['source_type' => 'manual'],
        ]);

        $this->add_control('rating_scale', [
            'label' => esc_html__('Rating Scale', 'sitsel'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                '5' => '5 Stars',
                '10' => '10 Stars',
            ],
            'default' => '5',
            'condition' => ['rating' => 'yes'],
              'condition' => [
                'rating' => 'yes',
                'source_type' => 'manual',
            ],
        ]);

        $this->end_controls_section();
        // === STYLE TAB START ===
        $this->start_controls_section('style_testimonial', [
                'label' => esc_html__('Testimonial Box', 'sitsel'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]);


        $this->add_control('testimonial_bg', [
            'label' => esc_html__('Background Color', 'sitsel'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .sitsel-testimonial-box' => 'background-color: {{VALUE}};',
            ],
            'default' => "#fff",
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name' => 'testimonial_box_shadow',
            'selector' => '{{WRAPPER}} .sitsel-testimonial-box',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'testimonial_border',
            'selector' => '{{WRAPPER}} .sitsel-testimonial-box',
        ]);

        $this->add_responsive_control('testimonial_padding', [
            'label' => esc_html__('Padding', 'sitsel'),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{WRAPPER}} .sitsel-testimonial-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();


        // === AUTHOR NAME ===
        $this->start_controls_section('style_author', [
            'label' => esc_html__('Author Name', 'sitsel'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('author_color', [
            'label' => esc_html__('Color', 'sitsel'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .sitsel-testimonial-author' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'author_typography',
            'selector' => '{{WRAPPER}} .sitsel-testimonial-author',
        ]);

        $this->end_controls_section();


        // === JOB TITLE ===
        $this->start_controls_section('style_job', [
            'label' => esc_html__('Job Title', 'sitsel'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('job_color', [
            'label' => esc_html__('Color', 'sitsel'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .sitsel-testimonial-job' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'job_typography',
            'selector' => '{{WRAPPER}} .sitsel-testimonial-job',
        ]);

        $this->end_controls_section();


        // === TESTIMONIAL TITLE ===
        $this->start_controls_section('style_title', [
            'label' => esc_html__('Testimonial Title', 'sitsel'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('title_color', [
            'label' => esc_html__('Color', 'sitsel'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .sitsel-testimonial-title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'selector' => '{{WRAPPER}} .sitsel-testimonial-title',
        ]);

        $this->end_controls_section();


        // === CONTENT ===
        $this->start_controls_section('style_content', [
            'label' => esc_html__('Content', 'sitsel'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('content_color', [
            'label' => esc_html__('Color', 'sitsel'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .sitsel-testimonial-content' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'content_typography',
            'selector' => '{{WRAPPER}} .sitsel-testimonial-content',
        ]);

        $this->end_controls_section();


        // === AUTHOR IMAGE ===
        $this->start_controls_section('style_author_img', [
            'label' => esc_html__('Author Image', 'sitsel'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('author_img_size', [
            'label' => esc_html__('Size (px)', 'sitsel'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 20, 'max' => 150]],
            'selectors' => [
                '{{WRAPPER}} .sitsel-author-img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; object-fit: cover; border-radius: 50%;',
            ],
        ]);

        $this->end_controls_section();


        // === QUOTE ICON ===
        $this->start_controls_section('style_quote_icon', [
            'label' => esc_html__('Quote Icon', 'sitsel'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        // Icon Color (affects font icons and SVG)
        $this->add_control('quote_icon_color', [
            'label' => esc_html__('Color', 'sitsel'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .sitsel-quote-icon path' => 'color: {{VALUE}}; fill: {{VALUE}};',
                '{{WRAPPER}} .sitsel-quote-icon svg path' => 'fill: {{VALUE}};',
            ],
        ]);

        // Icon Size
       $this->add_responsive_control('quote_icon_size', [
            'label' => esc_html__('Size', 'sitsel'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 10,
                    'max' => 150,
                ],
            ],
            'default' => [
                'size' => 20,
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .sitsel-quote-icon' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .sitsel-quote-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);


        $this->end_controls_section();



        // === COMPANY LOGO ===
        $this->start_controls_section('style_company_logo', [
            'label' => esc_html__('Company Logo', 'sitsel'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('company_logo_size', [
            'label' => esc_html__('Size', 'sitsel'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 10, 'max' => 150]],
            'selectors' => [
                '{{WRAPPER}} .sitsel-company-logo' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->end_controls_section();


        // === STAR RATING ===
        $this->start_controls_section('style_rating', [
            'label' => esc_html__('Rating Stars', 'sitsel'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('star_color', [
            'label' => esc_html__('Star Color', 'sitsel'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .sitsel-star.filled' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('star_empty_color', [
            'label' => esc_html__('Empty Star Color', 'sitsel'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .sitsel-star' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('star_size', [
            'label' => esc_html__('Size', 'sitsel'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 10, 'max' => 50]],
            'selectors' => [
                '{{WRAPPER}} .sitsel-star' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        // pagination style 

        $this->start_controls_section('style_pagination', [
            'label' => esc_html__('Pagination Dots', 'sitsel'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('pagination_color', [
            'label' => esc_html__('Dot Color', 'sitsel'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('pagination_active_color', [
            'label' => esc_html__('Active Dot Color', 'sitsel'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('pagination_spacing', [
            'label' => esc_html__('Spacing (Margin Top)', 'sitsel'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 100]],
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        // navigation style
        $this->start_controls_section('style_navigation', [
            'label' => esc_html__('Navigation Arrows', 'sitsel'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('nav_arrow_color', [
            'label' => esc_html__('Arrow Color', 'sitsel'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-prev' => 'color: {{VALUE}};',
                '{{WRAPPER}} .swiper-button-next::after, {{WRAPPER}} .swiper-button-prev::after' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('nav_arrow_bg', [
            'label' => esc_html__('Arrow Background', 'sitsel'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-prev' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('nav_arrow_size', [
            'label' => esc_html__('Arrow Size', 'sitsel'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 10, 'max' => 100]],
            'selectors' => [
                '{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .swiper-button-next::after, {{WRAPPER}} .swiper-button-prev::after' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('nav_arrow_position', [
            'label' => esc_html__('Vertical Position (Top)', 'sitsel'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['%' => ['min' => 0, 'max' => 100]],
            'selectors' => [
                '{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-prev' => 'top: {{SIZE}}%;',
            ],
        ]);

        $this->end_controls_section();

    }

protected function render() {
    $settings = $this->get_settings_for_display();

    $effect           = $settings['effect'] ?? 'slide';
    $loop             = $settings['loop'] === 'yes';
    $pagination       = $settings['pagination'] === 'yes';
    $navigation       = $settings['navigation'] === 'yes';
    $transition_speed = floatval($settings['transition_speed'] ?? 0.7) * 1000;
    $source_type      = $settings['source_type'];

    echo '<div class="swiper sitsel-testimonial-slider"
        data-effect="' . esc_attr($effect) . '"
        data-loop="' . esc_attr($loop ? 'true' : 'false') . '"
        data-speed="' . esc_attr($transition_speed) . '"
        data-pagination="' . esc_attr($pagination ? 'true' : 'false') . '"
        data-navigation="' . esc_attr($navigation ? 'true' : 'false') . '"
        data-columns-desktop="' . esc_attr($settings['columns']) . '"
        data-columns-tablet="' . esc_attr($settings['columns_tablet'] ?? $settings['columns']) . '"
        data-columns-mobile="' . esc_attr($settings['columns_mobile'] ?? $settings['columns']) . '"
        data-scroll="' . esc_attr($settings['slides_to_scroll']) . '"
        data-gutter="' . esc_attr($settings['gutter']['size']) . '">';

    echo '<div class="swiper-wrapper">';

    if ($source_type === 'dynamic') {
        $query = new \WP_Query([
            'post_type'      => $settings['dynamic_post_type'],
            'posts_per_page' => $settings['posts_per_page'],
        ]);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                echo '<div class="swiper-slide">';
                if (!empty($settings['template_id'])) {
                    echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($settings['template_id']);
                } else {
                    echo '<div class="sitsel-testimonial-box">';
                    the_title('<h4 class="sitsel-testimonial-title">', '</h4>');
                    echo '<div class="sitsel-testimonial-content">' . get_the_content() . '</div>';
                    echo '</div>';
                }
                echo '</div>'; // swiper-slide
            }
            wp_reset_postdata();
        }
    } else {
        if (!empty($settings['testimonials']) && is_array($settings['testimonials'])) {
            foreach ($settings['testimonials'] as $item) {
                $title        = $item['title'] ?? '';
                $content      = $item['content'] ?? '';
                $author       = $item['author'] ?? '';
                $job          = $item['job'] ?? '';
                $rating       = $item['rating'] ?? '';
                $author_img   = $item['author_image']['url'] ?? plugin_dir_url(dirname(__DIR__)) . 'assets/images/placeholder-image.webp';
                $company_logo = $item['company_logo']['url'] ?? '';

                echo '<div class="swiper-slide">';
                echo '<div class="sitsel-testimonial-box">';

                // Company Logo
                if ($company_logo) {
                    echo '<img class="sitsel-company-logo" src="' . esc_url($company_logo) . '" alt="Company Logo" />';
                }

                // Quote Icon
                if (!empty($settings['quote_icon']['value'])) {
                    echo '<span class="sitsel-quote-icon">';
                    \Elementor\Icons_Manager::render_icon($settings['quote_icon'], ['aria-hidden' => 'true']);
                    echo '</span>';
                }

                // Title
                if ($title) {
                    echo '<div class="sitsel-testimonial-title">' . esc_html($title) . '</div>';
                }

                // Content
                if ($content) {
                    echo '<div class="sitsel-testimonial-content">' . wp_kses_post($content) . '</div>';
                }

                // Meta
                echo '<div class="sitsel-testimonial-meta">';
                    echo '<div class="sitsel-author-profile">';
                    if ($author_img) {
                        echo '<img class="sitsel-author-img" src="' . esc_url($author_img) . '" alt="' . esc_attr($author) . '" />';
                    }
                    echo '</div>';

                    echo '<div>';
                    if ($author) {
                        echo '<h4 class="sitsel-testimonial-author">' . esc_html($author) . '</h4>';
                    }
                    if ($job) {
                        echo '<span class="sitsel-testimonial-job">' . esc_html($job) . '</span>';
                    }

                    // Rating
                    if (!empty($settings['rating']) && $settings['rating'] === 'yes' && $rating) {
                        $max   = (int) ($settings['rating_scale'] ?? 5);
                        $score = (float) $rating;
                        echo '<div class="sitsel-testimonial-rating">';
                        for ($i = 1; $i <= $max; $i++) {
                            echo '<span class="sitsel-star' . ($i <= $score ? ' filled' : '') . '">&#9733;</span>';
                        }
                        echo '</div>';
                    }
                    echo '</div>'; // close author/job
                echo '</div>'; // close testimonial-meta

                echo '</div>'; // close testimonial-box
                echo '</div>'; // close swiper-slide
            }
        }
    }

    echo '</div>'; // .swiper-wrapper

    if ($pagination) {
        echo '<div class="swiper-pagination"></div>';
    }

    if ($navigation) {
        echo '<div class="swiper-button-prev"></div>';
        echo '<div class="swiper-button-next"></div>';
    }

    echo '</div>'; // .swiper
}






    public function get_script_depends()
    {
        return ['swiper'];
    }

    public function get_style_depends()
    {
        return ['swiper'];
    }
    protected function get_elementor_templates() {
    $templates = [];
    $posts = get_posts([
        'post_type' => 'elementor_library',
        'posts_per_page' => -1,
    ]);

    foreach ($posts as $post) {
        $templates[$post->ID] = $post->post_title;
    }

    return $templates;
}

}

