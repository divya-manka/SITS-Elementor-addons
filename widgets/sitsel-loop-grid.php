<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class sitsel_Loop_Grid_Widget extends Widget_Base {

	public function get_name() {
		return 'sitsel_loop_grid';
	}

	public function get_title() {
		return esc_html__( 'Sitsel Loop Grid', 'sitsel' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function get_script_depends() {
		return [ 'sitsel-post-grid' ];
	}

	public function _register_controls() {
		$this->start_controls_section(
			'sitsel_layout_section',
			[
				'label' => esc_html__( 'Layout', 'sitsel' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'sitsel_post_type',
			[
				'label' => esc_html__( 'Choose template type', 'sitsel' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->sitsel_get_post_types(),
				'default' => 'post',
			]
		);

		$this->add_control(
			'sitsel_loop_template',
			[
				'label' => esc_html__( 'Choose a template', 'sitsel' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'options' => $this->sitsel_get_loop_templates(),
			]
		);

		$this->add_control(
			'sitsel_edit_template',
			[
				'type' => Controls_Manager::BUTTON,
				'label' => esc_html__( 'Edit Template', 'sitsel' ),
				'text' => esc_html__( 'Edit template', 'sitsel' ),
				'event' => 'sitsel:editTemplate',
				'separator' => 'after',
				'condition' => [
					'sitsel_loop_template!' => ''
				]
			]
		);

		$this->add_responsive_control(
			'sitsel_columns',
			[
				'label' => esc_html__( 'Columns', 'sitsel' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
			]
		);

		$this->add_control(
			'sitsel_posts_per_page',
			[
				'label' => esc_html__( 'Items Per Page', 'sitsel' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 6,
			]
		);

		$this->add_control(
			'sitsel_masonry',
			[
				'label' => esc_html__( 'Masonry', 'sitsel' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		$this->add_control(
			'sitsel_equal_height',
			[
				'label' => esc_html__( 'Equal height', 'sitsel' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		$this->end_controls_section();

		// Style Section

		$this->start_controls_section(
			'sitsel_title_style_section',
			[
				'label' => esc_html__( 'Post Title', 'sitsel' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'sitsel_post_title_color',
			[
				'label' => esc_html__( 'Text Color', 'sitsel' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sitsel-post-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sitsel_post_title_typography',
				'label' => esc_html__( 'Typography', 'sitsel' ),
				'selector' => '{{WRAPPER}} .sitsel-post-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'sitsel_post_title_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'sitsel' ),
				'selector' => '{{WRAPPER}} .sitsel-post-title',
			]
		);

		$this->add_responsive_control(
			'sitsel_post_title_align',
			[
				'label' => esc_html__( 'Alignment', 'sitsel' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'sitsel' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'sitsel' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'sitsel' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sitsel-post-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// for post expert 
		$this->start_controls_section(
			'sitsel_excerpt_style_section',
			[
				'label' => esc_html__( 'Excerpt', 'sitsel' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'sitsel_excerpt_color',
			[
				'label' => esc_html__( 'Text Color', 'sitsel' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sitsel-post-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sitsel_excerpt_typography',
				'selector' => '{{WRAPPER}} .sitsel-post-excerpt',
			]
		);

		$this->end_controls_section();

		// for category styles
			$this->start_controls_section(
				'sitsel_category_style_section',
				[
					'label' => esc_html__( 'Categories', 'sitsel' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'sitsel_category_color',
				[
					'label' => esc_html__( 'Color', 'sitsel' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .sitsel-category' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'sitsel_category_typography',
					'selector' => '{{WRAPPER}} .sitsel-category',
				]
			);

			$this->end_controls_section();

		//  for image styles 
		$this->start_controls_section(
			'sitsel_featured_image_style_section',
			[
				'label' => esc_html__( 'Featured Image', 'sitsel' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sitsel_image_width',
			[
				'label' => esc_html__( 'Width', 'sitsel' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .sitsel-post-thumbnail img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'sitsel_image_border',
				'selector' => '{{WRAPPER}} .sitsel-post-thumbnail img',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'sitsel_image_shadow',
				'selector' => '{{WRAPPER}} .sitsel-post-thumbnail img',
			]
		);

		$this->end_controls_section();


      // for pagination styles
		$this->start_controls_section(
			'sitsel_style_section',
			[
				'label' => esc_html__( 'Pagination', 'sitsel' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sitsel_pagination_typography',
				'label' => esc_html__( 'Typography', 'sitsel' ),
				'selector' => '{{WRAPPER}} .sitsel-pagination',
			]
		);

		$this->add_control(
			'sitsel_pagination_colors',
			[
				'label' => esc_html__( 'Colors', 'sitsel' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sitsel-pagination a' => 'color: {{VALUE}};',
				],
			]
		);

		// $this->add_responsive_control(
		// 	'sitsel_space_between',
		// 	[
		// 		'label' => esc_html__( 'Space Between', 'sitsel' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'selectors' => [
		// 			'{{WRAPPER}} .sitsel-post-grid' => 'margin: 0 {{SIZE}}{{UNIT}};',
		// 		],
		// 	]
		// );

		$this->add_responsive_control(
			'sitsel_spacing',
			[
				'label' => esc_html__( 'Spacing', 'sitsel' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .sitsel-post-grid' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
	$settings = $this->get_settings_for_display();
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $args = [
        'post_type' => $settings['sitsel_post_type'],
	    'posts_per_page' => $settings['sitsel_posts_per_page'],
        'paged'          => $paged,
    ];
		

		$query = new WP_Query($args);
		echo '<div class="sitsel-post-grid" style="display: grid; grid-template-columns: repeat(' . esc_attr($settings['sitsel_columns']) . ', 1fr);">';

		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();

				if ($settings['sitsel_loop_template']) {
					echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($settings['sitsel_loop_template']);
				} else {
					// fallback if no template selected
					echo '<div class="sitsel-post-grid-item">';
	
							// Featured image
							if (has_post_thumbnail()) {
								echo '<div class="sitsel-post-thumbnail">';
								the_post_thumbnail('medium'); // You can use 'full', 'large', etc.
								echo '</div>';
							}

							// Post title
							echo '<h3 class="sitsel-post-title">' . get_the_title() . '</h3>';

							// Categories
							$categories = get_the_category();
							if ($categories) {
								echo '<div class="sitsel-post-categories">';
								foreach ($categories as $category) {
									echo '<span class="sitsel-category">' . esc_html($category->name) . '</span> ';
								}
								echo '</div>';
							}

							// Excerpt
							echo '<div class="sitsel-post-excerpt">' . get_the_excerpt() . '</div>';

							echo '</div>';


				}
			}
                // pagination
			 $total_pages = $query->max_num_pages;

				if ( $total_pages > 1 ) {
					echo '<div class="sitsel-pagination">';

					echo paginate_links([
						'total'   => $total_pages,
						'current' => $paged,
						'format'  => '?paged=%#%',
						'type'    => 'list',
						'prev_text' => __('« Prev'),
						'next_text' => __('Next »'),
					]);

					echo '</div>';
				}

			wp_reset_postdata();
		} else {
			echo '<p>No posts found.</p>';
		}

		echo '</div>';
	}

	private function sitsel_get_post_types() {
		$post_types = get_post_types(['public' => true], 'objects');
		$options = [];
		foreach ($post_types as $slug => $pt) {
			$options[$slug] = $pt->labels->singular_name;
		}
		return $options;
	}

	private function sitsel_get_loop_templates() {
		$options = [];
		$templates = get_posts([
			'post_type' => 'elementor_library',
			'numberposts' => -1,
		]);
		foreach ($templates as $template) {
			$options[$template->ID] = $template->post_title;
		}
		return $options;
	}
}
