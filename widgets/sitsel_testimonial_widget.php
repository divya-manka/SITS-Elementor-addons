<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

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

    public function get_categories()
    {
        return ['general'];
    }

    public function _register_controls()
    {
        // Layout Type
        $this->start_controls_section('content_section', [
            'label' => esc_html__('Testimonials Content', 'sitsel'),
        ]);

        $this->add_control('source_type', [
            'label' => esc_html__('Source', 'sitsel'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'manual' => esc_html__('Manual Input', 'sitsel'),
                'dynamic' => esc_html__('From Post Type', 'sitsel'),
            ],
            'default' => 'manual',
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
            'default' => ['url' => 'https://via.placeholder.com/100'],
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
            'default' => ['url' => 'https://via.placeholder.com/150'],
        ]);


        $this->add_control('testimonials', [
            'label' => esc_html__('Testimonials', 'sitsel'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'condition' => ['source_type' => 'manual'],
        ]);

        // Dynamic (Post) Source
        $this->add_control('post_type', [
            'label' => esc_html__('Select Post Type', 'sitsel'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'testimonial' => 'Testimonial',
                'post' => 'Post',
                'page' => 'Page',
            ],
            'default' => 'testimonial',
            'condition' => ['source_type' => 'dynamic'],
        ]);

        $this->add_control('posts_per_page', [
            'label' => esc_html__('Number of Testimonials', 'sitsel'),
            'type' => Controls_Manager::NUMBER,
            'default' => 5,
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
            'default' => 'yes',
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
            'type' => Controls_Manager::SELECT,
            'options' => [
                '1' => 'One',
                '2' => 'Two',
                '3' => 'Three',
            ],
            'default' => '2',
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
            'default' => 'yes',
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
                'type' => \Elementor\Controls_Manager::MEDIA,
                'media_types' => ['image'],
                'show_label' => true,
            ]
        );




        $this->add_control('rating', [
            'label' => esc_html__('Rating', 'sitsel'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
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
        ]);

        // $this->add_control('icon_style', [
//     'label' => esc_html__('Icon Style', 'sitsel'),
//     'type' => Controls_Manager::SELECT,
//     'options' => [
//         'style1' => 'Style 1',
//         'style2' => 'Style 2',
//     ],
//     'default' => 'style2',
// ]);


        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $is_dynamic = ($settings['source_type'] === 'dynamic');
        $image_size = $settings['image_size'] ?? 'full';
        $effect = $settings['effect'] ?? 'slide';
        $loop = $settings['loop'] === 'yes';
        $pagination = $settings['pagination'] === 'yes';
        $navigation = $settings['navigation'] === 'yes';
        $transition_speed = floatval($settings['transition_speed'] ?? 0.7) * 1000;

        echo '<div class="swiper sitsel-testimonial-slider"
    data-effect="' . esc_attr($effect) . '"
    data-loop="' . esc_attr($loop ? 'true' : 'false') . '"
    data-speed="' . esc_attr($transition_speed) . '"
    data-pagination="' . esc_attr($pagination ? 'true' : 'false') . '"
    data-navigation="' . esc_attr($navigation ? 'true' : 'false') . '"
    data-columns="' . esc_attr($settings['columns']) . '"
    data-scroll="' . esc_attr($settings['slides_to_scroll']) . '"
    data-gutter="' . esc_attr($settings['gutter']['size']) . '">';


        echo '<div class="swiper-wrapper">';

        if ($is_dynamic) {
            $query = new WP_Query([
                'post_type' => $settings['post_type'],
                'posts_per_page' => $settings['posts_per_page'],
            ]);

            while ($query->have_posts()):
                $query->the_post();

                $title = get_the_title();
                $content = get_the_content();
                $author = get_field('author'); // Adjust field name
                $job = get_field('job');
                $rating = get_field('rating');
                $author_img = get_field('author_image')['url'];
                $company_logo = get_field('company_logo')['url'];

                echo '<div class="swiper-slide">';
                echo '<div class="sitsel-testimonial-box">';

                if ($company_logo) {
                    echo '<img class="sitsel-company-logo" src="' . esc_url($company_logo) . '" alt="Company Logo" />';
                }

                if (!empty($settings['quote_icon']['url'])) {
                    echo '<img class="sitsel-quote-icon" src="' . esc_url($settings['quote_icon']['url']) . '" alt="Quote Icon" />';
                }

                echo '<div class="sitsel-testimonial-title">' . esc_html($title) . '</div>';
                echo '<div class="sitsel-testimonial-content">' . wp_kses_post($content) . '</div>';

                echo '<div class="sitsel-testimonial-meta">';
                echo '<div class="sitsel-author-profile">';
                if ($author_img) {
                    echo '<img class="sitsel-author-img" src="' . esc_url($author_img) . '" alt="' . esc_attr($author) . '" />';
                }
                echo '</div>';

                echo '<div>';
                echo '<h4 class="sitsel-testimonial-author">' . esc_html($author) . '</h4>';
                echo '<span class="sitsel-testimonial-job">' . esc_html($job) . '</span>';

                if ($settings['rating'] === 'yes' && $rating) {
                    echo '<div class="sitsel-testimonial-rating">';
                    $max = (int) $settings['rating_scale'];
                    $score = (float) $rating;
                    for ($i = 1; $i <= $max; $i++) {
                        echo '<span class="sitsel-star' . ($i <= $score ? ' filled' : '') . '">&#9733;</span>';
                    }
                    echo '</div>';
                }
                echo '</div>'; // Close author name + job + rating block
                echo '</div>'; // Close testimonial-meta
                echo '</div>'; // Close testimonial-box
                echo '</div>'; // Close swiper-slide


            endwhile;
            wp_reset_postdata();
        } else {
            foreach ($settings['testimonials'] as $item) {
                $title = $item['title'];
                $content = $item['content'];
                $author = $item['author'];
                $job = $item['job'];
                $rating = $item['rating'];
                $author_img = $item['author_image']['url'];
                $company_logo = $item['company_logo']['url'];

                echo '<div class="swiper-slide">';
                echo '<div class="sitsel-testimonial-box">';

                if ($company_logo) {
                    echo '<img class="sitsel-company-logo" src="' . esc_url($company_logo) . '" alt="Company Logo" />';
                }

                if (!empty($settings['quote_icon']['url'])) {
                    echo '<img class="sitsel-quote-icon" src="' . esc_url($settings['quote_icon']['url']) . '" alt="Quote Icon" />';
                }

                echo '<div class="sitsel-testimonial-title">' . esc_html($title) . '</div>';
                echo '<div class="sitsel-testimonial-content">' . wp_kses_post($content) . '</div>';

                echo '<div class="sitsel-testimonial-meta">';
                echo '<div class="sitsel-author-profile">';
                if ($author_img) {
                    echo '<img class="sitsel-author-img" src="' . esc_url($author_img) . '" alt="' . esc_attr($author) . '" />';
                }
                echo '</div>';

                echo '<div>';
                echo '<h4 class="sitsel-testimonial-author">' . esc_html($author) . '</h4>';
                echo '<span class="sitsel-testimonial-job">' . esc_html($job) . '</span>';

                if ($settings['rating'] === 'yes' && $rating) {
                    echo '<div class="sitsel-testimonial-rating">';
                    $max = (int) $settings['rating_scale'];
                    $score = (float) $rating;
                    for ($i = 1; $i <= $max; $i++) {
                        echo '<span class="sitsel-star' . ($i <= $score ? ' filled' : '') . '">&#9733;</span>';
                    }
                    echo '</div>';
                }
                echo '</div>'; // Close author name + job + rating block
                echo '</div>'; // Close testimonial-meta
                echo '</div>'; // Close testimonial-box
                echo '</div>'; // Close swiper-slide

            }


        }

        echo '</div>'; // .swiper-wrapper
        echo '<div class="swiper-pagination"></div>';
        if ($settings['navigation'] === 'yes') {
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
}

add_action('elementor/frontend/after_enqueue_scripts', function () {
    ?>
    <script>
        (function ($) {
            const initSwiper = function ($scope) {
                $scope.find('.sitsel-testimonial-slider').each(function () {
                    const $slider = $(this);
                    const slidesPerView = parseInt($slider.data('columns')) || 1;
                    const slidesToScroll = parseInt($slider.data('scroll')) || 1;
                    const spaceBetween = parseInt($slider.data('gutter')) || 15;
                    const loop = $slider.data('loop') === true || $slider.data('loop') === 'true';
                    const effect = $slider.data('effect') || 'slide';
                    const speed = parseInt($slider.data('speed')) || 700;
                    const pagination = $slider.data('pagination') === true || $slider.data('pagination') === 'true';
                    const navigation = $slider.data('navigation') === true || $slider.data('navigation') === 'true';

                    new Swiper($slider[0], {
                        slidesPerView: slidesPerView,
                        slidesPerGroup: slidesToScroll,
                        spaceBetween: spaceBetween,
                        loop: loop,
                        effect: effect,
                        speed: speed,
                        pagination: pagination ? {
                            el: $slider.find('.swiper-pagination')[0],
                            clickable: true
                        } : false,
                        navigation: navigation ? {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev'
                        } : false,
                    });
                });
            };

            $(window).on('elementor/frontend/init', function () {
                elementorFrontend.hooks.addAction('frontend/element_ready/sitsel_testimonial.default', initSwiper);
            });
        })(jQuery);
    </script>
    <?php
});
