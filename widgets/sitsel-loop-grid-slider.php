<?php
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

    public function get_categories()
    {
        return ['sitsel'];
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
                    // 'condition' => [
                    //     'layout' => 'grid', // Only show for grid layout
                    // ],
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
                'label' => esc_html__('Pagination Type', 'sitsel'),
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
                'label' => esc_html__('Post Title', 'sits-el'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'sits-el'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sitsel-post-title' => 'color: {{VALUE}};',
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
        $this->end_controls_section();
        

    }

  protected function render() {
    $settings = $this->get_settings_for_display();
    $gap = isset($settings['gap']) ? (int) $settings['gap'] : 20;
    $columns = isset($settings['columns']) ? (int) $settings['columns'] : 3;

   $paged = get_query_var('paged') ? get_query_var('paged') : 1;

    $args = [
        'post_type'      => $settings['post_type'],
        'posts_per_page' => $settings['posts_per_page'],
        'paged'          => $paged,
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
                echo '<div class="swiper sitsel-slider" data-gap="' . esc_attr($gap) . '" data-slidesPerView="' . esc_attr($columns) . '">';
                echo '<div class="swiper-wrapper">';

                while ($query->have_posts()) {
                    $query->the_post();
                    $title = get_the_title();
                    $img_url = get_the_post_thumbnail_url(get_the_ID(), 'large');

                    echo '<div class="swiper-slide">';
                    echo '<div class="sitsel-slide-inner">';
                    if ($img_url) {
                        echo '<img src="' . esc_url($img_url) . '" alt="' . esc_attr($title) . '" />';
                    }
                    echo '<h3 class="sitsel-post-title">' . esc_html($title) . '</h3>';
                    echo '</div>';
                    echo '</div>';
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
            }
            else {
                echo '<div class="sitsel-grid" >';
                while ($query->have_posts()) {
                    $query->the_post();
                    $title = get_the_title();
                    $img_url = get_the_post_thumbnail_url(get_the_ID(), 'large');

                    echo '<div class="grid-slide">';
                    echo '<div class="sitsel-grid-inner">';
                    if ($img_url) {
                        echo '<img src="' . esc_url($img_url) . '" alt="' . esc_attr($title) . '" />';
                    }
                    echo '<h3 class="sitsel-post-title">' . esc_html($title) . '</h3>';
                    echo '</div>';
                    echo '</div>';
                }
                echo'</div>';
                  if ($settings['layout'] === 'grid' && $settings['pagination'] === 'yes') {
                    $total_pages = $query->max_num_pages;

                    if ($total_pages > 1) {
                        echo '<div class="sitsel-pagination">';
                        echo paginate_links([
                            'total'     => $total_pages,
                            'current'   => $paged,
                            'format'    => '?paged=%#%',
                            'type'      => 'list',
                            'prev_text' => esc_html__('« Prev', 'sitsel'),
                            'next_text' => esc_html__('Next »', 'sitsel'),
                        ]);
                        echo '</div>';
                    }
                }


            }

  

     
 
    wp_reset_postdata();

    // Swiper initialization if needed
    if ($is_slider) {
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const sliderEl = document.querySelector('.sitsel-slider');
                const spaceBetween = parseInt(sliderEl?.getAttribute('data-gap')) || 20;
                const slidesPerView = parseInt(sliderEl?.getAttribute('data-slidesPerView')) || 3;

                new Swiper('.sitsel-slider', {
                    loop: true,
                    slidesPerView: slidesPerView,
                    spaceBetween: spaceBetween,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 2,
                            spaceBetween: spaceBetween
                        },
                        1024: {
                            slidesPerView: slidesPerView,
                            spaceBetween: spaceBetween
                        }
                    }
                });
            });
        </script>

        <?php
    }
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