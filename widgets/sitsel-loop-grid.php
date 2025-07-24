<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Repeater;

if (!defined('ABSPATH'))
	exit;

class sitsel_Loop_Grid_Widget extends Widget_Base
{

	public function get_name()
	{
		return 'sitsel_loop_grid';
	}

	public function get_title()
	{
		return esc_html__('Sitsel Loop Grid', 'sitsel');
	}

	public function get_icon()
	{
		return 'eicon-posts-grid';
	}

	public function get_categories()
	{
		return ['sits-category'];
	}

	public function get_script_depends()
	{
		return ['sitsel-post-grid'];
	}

	protected function _register_controls()
	{

		// CONTENT TAB - Layout Section
		$this->start_controls_section(
			'sitsel_layout_section',
			[
				'label' => esc_html__('Layout', 'sitsel'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'sitsel_post_type',
			[
				'label' => esc_html__('Choose Post Type', 'sitsel'),
				'type' => Controls_Manager::SELECT,
				'options' => $this->sitsel_get_post_types(),
				'default' => 'post',
			]
		);

		$this->add_control(
			'sitsel_loop_template',
			[
				'label' => esc_html__('Choose a Template', 'sitsel'),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'options' => $this->sitsel_get_loop_templates(),
			]
		);

		$this->add_responsive_control(
			'sitsel_columns',
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
					'{{WRAPPER}} .sitsel-post-grid' => 'display: grid; grid-template-columns: repeat({{VALUE}}, 1fr); gap: 20px;',
				],

			]
		);

		$this->add_control(
			'sitsel_posts_per_page',
			[
				'label' => esc_html__('Items Per Page', 'sitsel'),
				'type' => Controls_Manager::NUMBER,
				'default' => 6,
			]
		);

		$this->add_control(
			'sitsel_masonry',
			[
				'label' => esc_html__('Masonry', 'sitsel'),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'sitsel_equal_height',
			[
				'label' => esc_html__('Equal Height', 'sitsel'),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->end_controls_section();


		// elements tab
		$this->start_controls_section('section_post_elements', [
			'label' => esc_html__('Elements', 'sitsel'),
		]);


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
			'featured_image' => __('Featured Image', 'sitsel'),
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
				['element_type' => 'featured_image'],
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

		// $this->add_group_control(
//     \Elementor\Group_Control_Background::get_type(),
//     [
//         'name' => 'custom_field_background',
//         'label' => esc_html__('Background', 'sitsel'),
//         'types' => ['classic', 'gradient'],
//         'selector' => '{{WRAPPER}} .sitsel-post-custom-field',
//     ]
// );

		// $this->add_group_control(
//     \Elementor\Group_Control_Border::get_type(),
//     [
//         'name' => 'custom_field_border',
//         'selector' => '{{WRAPPER}} .sitsel-post-custom-field',
//     ]
// );

		// $this->add_control(
//     'custom_field_padding',
//     [
//         'label' => esc_html__('Padding', 'sitsel'),
//         'type' => \Elementor\Controls_Manager::DIMENSIONS,
//         'selectors' => [
//             '{{WRAPPER}} .sitsel-post-custom-field' => 'padding: {{TOP}} {{RIGHT}} {{BOTTOM}} {{LEFT}};',
//         ],
//     ]
// );

		$this->end_controls_section();


		// read more btn control style tabs
		$this->start_controls_section('style_readmore', [
			'label' => esc_html__('Read More', 'sitsel'),
			'tab' => Controls_Manager::TAB_STYLE,
		]);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'readmore_typography',
				'selector' => '{{WRAPPER}} .sitsel-post-readmore a',
			]
		);

		// Color (Normal / Hover)
		$this->start_controls_tabs('tabs_readmore_color');

		$this->start_controls_tab('tab_readmore_color_normal', [
			'label' => esc_html__('Normal', 'sitsel'),
		]);

		$this->add_control('readmore_color', [
			'label' => esc_html__('Color', 'sitsel'),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .sitsel-post-readmore a' => 'color: {{VALUE}};',
			],
		]);

		$this->end_controls_tab();

		$this->start_controls_tab('tab_readmore_color_hover', [
			'label' => esc_html__('Hover', 'sitsel'),
		]);

		$this->add_control('readmore_color_hover_color', [
			'label' => esc_html__('Hover Color', 'sitsel'),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .sitsel-post-readmore a:hover' => 'color: {{VALUE}};',
			],
		]);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		
		// Background Color (Normal / Hover)
		$this->start_controls_tabs('tabs_readmore_background');

		$this->start_controls_tab('tab_readmore_background_normal', [
			'label' => esc_html__('Normal', 'sitsel'),
		]);

		$this->add_control('readmore_background_color', [
			'label' => esc_html__('Background Color', 'sitsel'),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .sitsel-post-readmore a' => 'background-color: {{VALUE}};',
			],
		]);

		$this->end_controls_tab();

		$this->start_controls_tab('tab_readmore_background_hover', [
			'label' => esc_html__('Hover', 'sitsel'),
		]);

		$this->add_control('readmore_background_hover_color', [
			'label' => esc_html__('Background Hover Color', 'sitsel'),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .sitsel-post-readmore a:hover' => 'background-color: {{VALUE}};',
			],
		]);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// padding
		$this->add_responsive_control(
			'readmore_padding',
			[
				'label' => esc_html__('Padding', 'sitsel'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .sitsel-post-readmore a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'readmore_margin',
			[
				'label' => esc_html__('Margin', 'sitsel'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .sitsel-post-readmore' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'readmore_border',
				'label' => esc_html__('Border', 'sitsel'),
				'selector' => '{{WRAPPER}} .sitsel-post-readmore a',
			]
		);

		// Border radius
		$this->add_responsive_control(
			'readmore_border_radius',
			[
				'label' => esc_html__('Border Radius', 'sitsel'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .sitsel-post-readmore a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();




		// Pagination
		$this->start_controls_section(
			'sitsel_pagination_style_section',
			[
				'label' => esc_html__('Pagination', 'sitsel'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sitsel_pagination_typography',
				'label' => esc_html__('Typography', 'sitsel'),
				'selector' => '{{WRAPPER}} .sitsel-pagination .page-numbers',
			]
		);

		$this->add_control(
			'sitsel_pagination_color',
			[
				'label' => esc_html__('Text Color', 'sitsel'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sitsel-pagination .page-numbers' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sitsel_pagination_hover_color',
			[
				'label' => esc_html__('Hover Color', 'sitsel'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sitsel-pagination .page-numbers:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sitsel_pagination_hover_bg_color',
			[
				'label' => esc_html__('Hover Background', 'sitsel'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sitsel-pagination .page-numbers:hover' => 'background-color: {{VALUE}};',
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
				],
			]
		);


		$this->end_controls_section();
	}

	private function sitsel_get_loop_templates()
	{
		$templates = [];
		$posts = get_posts([
			'post_type' => 'elementor_library',
			'posts_per_page' => -1,
			'post_status' => 'publish',
		]);

		foreach ($posts as $post) {
			$templates[$post->ID] = $post->post_title;
		}

		return $templates;
	}

	private function sitsel_get_post_types()
	{
		$post_types = get_post_types(['public' => true], 'objects');
		$options = [];

		foreach ($post_types as $post_type) {
			$options[$post_type->name] = $post_type->label;
		}

		return $options;
	}

	public function render()
	{
		$settings = $this->get_settings_for_display();
		$paged = max(1, get_query_var('paged') ? get_query_var('paged') : get_query_var('page'));

		$args = [
			'post_type' => $settings['sitsel_post_type'],
			'posts_per_page' => $settings['sitsel_posts_per_page'],
			'paged' => $paged,
		];

		$query = new WP_Query($args);

		// Loop Output
		echo '<div class="sitsel-post-grid">';

		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();

				if (!empty($settings['sitsel_loop_template'])) {
					echo Plugin::$instance->frontend->get_builder_content_for_display($settings['sitsel_loop_template']);
				} else {
					// Fallback layout
					echo '<div class="sitsel-post-grid-item">';
					// if (!empty($settings['sitsel_feature_image']) && $settings['sitsel_feature_image'] === 'yes') {
					// 	if (has_post_thumbnail()) {
					// 		echo '<div class="sitsel-post-thumbnail">';
					// 		the_post_thumbnail('medium');
					// 		echo '</div>';
					// 	}
					// }

					foreach ($settings['post_elements'] as $item) {
						switch ($item['element_type']) {
							case 'featured_image':
								if (has_post_thumbnail()) {
									echo '<div class="sitsel-post-featured-image">';
									the_post_thumbnail('medium');
									echo '</div>';
								}
								break;

							case 'acf_image':
								if (!empty($item['custom_field_key_image']) && function_exists('get_field')) {
									$image = get_field($item['custom_field_key_image'], get_the_ID());
									if (!empty($image)) {
										// If it's an image array (from ACF Image field)
										if (is_array($image) && !empty($image['url'])) {
											echo '<div class="sitsel-post-acf-image">';
											echo '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt'] ?? '') . '" />';
											echo '</div>';
										}
										// If it's just a URL
										elseif (is_string($image)) {
											echo '<div class="sitsel-post-acf-image">';
											echo '<img src="' . esc_url($image) . '" alt="" />';
											echo '</div>';
										}
									}
								}
								break;
							case 'title':
								$tag = isset($item['html_tag']) ? $item['html_tag'] : 'h3';
								echo '<' . esc_attr($tag) . ' class="sitsel-post-title"><a href="' . get_permalink() . '">' . esc_html(get_the_title()) . '</a></' . esc_attr($tag) . '>';
								break;

							case 'content':
								echo '<div class="sitsel-post-content">' . get_the_content() . '</div>';
								break;

							case 'excerpt':
								echo '<div class="sitsel-post-excerpt">' . esc_html(get_the_excerpt()) . '</div>';
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

							case 'comments':
								$comments_number = get_comments_number();
								if ($comments_number > 0) {
									echo '<div class="sitsel-post-comments">' . esc_html($comments_number) . ' ' . __('Comments', 'sitsel') . '</div>';
								}
								break;


							case 'read_more':
								echo '<div class="sitsel-post-readmore"><a href="' . get_permalink() . '">' . esc_html__('Read More', 'sitsel') . '</a></div>';
								break;

							case 'category':
								echo '<div class="sitsel-post-category">' . get_the_category_list(', ') . '</div>';
								break;

							case 'tag':
								echo '<div class="sitsel-post-tags">' . get_the_tag_list('', ', ') . '</div>';
								break;

							case 'custom_field':
								if (!empty($item['custom_field_key'])) {
									$value = get_post_meta(get_the_ID(), $item['custom_field_key'], true);
									echo '<div class="sitsel-post-custom-field">' . esc_html($value) . '</div>';
								}
								break;

							default:
								// Check if it's an ACF field (stored as an element_type value)
								if (!empty($item['element_type']) && function_exists('get_field')) {
									$acf_value = get_field($item['element_type'], get_the_ID());
									if (!empty($acf_value)) {
										echo '<div class="sitsel-post-custom-field sitsel-post-field-' . esc_attr($item['element_type']) . '">' . esc_html($acf_value) . '</div>';
									}
								}
								break;
						}

					}

					echo '</div>'; // .sitsel-post-grid-item

				}
			}
			echo '</div>'; // .sitsel-post-grid

			// Pagination
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

			wp_reset_postdata();
		} else {
			echo '<p>' . esc_html__('No posts found.', 'sitsel') . '</p>';
			echo '</div>'; // .sitsel-post-grid
		}
	}
}
