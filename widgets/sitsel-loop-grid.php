<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit;

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
        return [ 'sits-category' ];
    }

	public function get_script_depends() {
		return [ 'sitsel-post-grid' ];
	}

	protected function _register_controls() {

		// CONTENT TAB - Layout Section
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
				'label' => esc_html__( 'Choose Post Type', 'sitsel' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->sitsel_get_post_types(),
				'default' => 'post',
			]
		);

		$this->add_control(
			'sitsel_loop_template',
			[
				'label' => esc_html__( 'Choose a Template', 'sitsel' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'options' => $this->sitsel_get_loop_templates(),
			]
		);

		// $this->add_control(
		// 	'edit_template_note',
		// 	[
		// 		'type' => Controls_Manager::RAW_HTML,
		// 		'raw' => '<div id="sitsel_edit_template_button_placeholder"></div>',
		// 		'separator' => 'after',
		// 		'condition' => [
		// 			'sitsel_loop_template!' => '',
		// 		],
		// 	]
		// );

		// $this->add_responsive_control(
		// 	'sitsel_columns',
		// 	[
		// 		'label' => esc_html__( 'Columns', 'sitsel' ),
		// 		'type' => Controls_Manager::NUMBER,
		// 		'default' => 3,
		// 		'min' => 1,
		// 		'max' => 6,
		// 	]
		// );
		 // Columns
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
			]
		);

		$this->add_control(
			'sitsel_equal_height',
			[
				'label' => esc_html__( 'Equal Height', 'sitsel' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->end_controls_section();

		// STYLE TAB - Pagination
		$this->start_controls_section(
			'sitsel_pagination_style_section',
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
				'selector' => '{{WRAPPER}} .sitsel-pagination .page-numbers',
			]
		);

		$this->add_control(
			'sitsel_pagination_color',
			[
				'label' => esc_html__( 'Text Color', 'sitsel' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sitsel-pagination .page-numbers' => 'color: {{VALUE}};',
				],
			]
		);

		// $this->add_control(
		// 	'sitsel_pagination_bg_color',
		// 	[
		// 		'label' => esc_html__( 'Background Color', 'sitsel' ),
		// 		'type' => Controls_Manager::COLOR,
		// 		'selectors' => [
		// 			'{{WRAPPER}} .sitsel-pagination .page-numbers' => 'background-color: {{VALUE}};',
		// 		],
		// 	]
		// );

		$this->add_control(
			'sitsel_pagination_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'sitsel' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sitsel-pagination .page-numbers:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sitsel_pagination_hover_bg_color',
			[
				'label' => esc_html__( 'Hover Background', 'sitsel' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sitsel-pagination .page-numbers:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sitsel_pagination_active_color',
			[
				'label' => esc_html__( 'Active Color', 'sitsel' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sitsel-pagination .page-numbers.current' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sitsel_pagination_active_bg',
			[
				'label' => esc_html__( 'Active Background', 'sitsel' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sitsel-pagination .page-numbers.current' => 'background-color: {{VALUE}};',
				],
			]
		);

		// $this->add_responsive_control(
		// 	'sitsel_pagination_spacing',
		// 	[
		// 		'label' => esc_html__( 'Item Spacing', 'sitsel' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'selectors' => [
		// 			'{{WRAPPER}} .sitsel-pagination li' => 'margin-right: {{SIZE}}{{UNIT}};',
		// 		],
		// 	]
		// );

		$this->end_controls_section();
	}

	private function sitsel_get_loop_templates() {
		$templates = [];
		$posts = get_posts([
			'post_type' => 'elementor_library',
			'posts_per_page' => -1,
			'post_status' => 'publish',
		]);

		foreach ( $posts as $post ) {
			$templates[ $post->ID ] = $post->post_title;
		}

		return $templates;
	}

	private function sitsel_get_post_types() {
		$post_types = get_post_types(['public' => true], 'objects');
		$options = [];

		foreach ( $post_types as $post_type ) {
			$options[ $post_type->name ] = $post_type->label;
		}

		return $options;
	}

	public function render() {
		$settings = $this->get_settings_for_display();
		$paged = max( 1, get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' ) );

		$args = [
			'post_type'      => $settings['sitsel_post_type'],
			'posts_per_page' => $settings['sitsel_posts_per_page'],
			'paged'          => $paged,
		];

		$query = new WP_Query( $args );

		// // Edit Template Button in Editor
		// if ( Plugin::instance()->editor->is_edit_mode() && ! empty( $settings['sitsel_loop_template'] ) ) {
		// 	$edit_url = admin_url( 'post.php?post=' . $settings['sitsel_loop_template'] . '&action=elementor' );

		// 	echo '<script>
		// 		document.addEventListener("DOMContentLoaded", function() {
		// 			let placeholder = document.querySelector("#sitsel_edit_template_button_placeholder");
		// 			if (placeholder) {
		// 				placeholder.innerHTML = `<a href="' . esc_url( $edit_url ) . '" target="_blank" class="elementor-button elementor-button-default" style="margin-top: 10px;">Edit Template in Elementor</a>`;
		// 			}
		// 		});
		// 	</script>';
		// }

		// Loop Output
	echo '<div class="sitsel-post-grid">';

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				if ( ! empty( $settings['sitsel_loop_template'] ) ) {
					echo Plugin::$instance->frontend->get_builder_content_for_display( $settings['sitsel_loop_template'] );
				} else {
					// Fallback layout
					echo '<div class="sitsel-post-grid-item">';
					if ( has_post_thumbnail() ) {
						echo '<div class="sitsel-post-thumbnail">';
						the_post_thumbnail( 'medium' );
						echo '</div>';
					}
					echo '<h3 class="sitsel-post-title">' . esc_html( get_the_title() ) . '</h3>';
					echo '<div class="sitsel-post-excerpt">' . esc_html( get_the_excerpt() ) . '</div>';
					echo '</div>';
				}
			}
			echo '</div>'; // .sitsel-post-grid

			// Pagination
			$total_pages = $query->max_num_pages;
			if ( $total_pages > 1 ) {
				echo '<div class="sitsel-pagination">';
				echo paginate_links( [
					'total'     => $total_pages,
					'current'   => $paged,
					'format'    => '?paged=%#%',
					'type'      => 'list',
					'prev_text' => esc_html__( '« Prev', 'sitsel' ),
					'next_text' => esc_html__( 'Next »', 'sitsel' ),
				] );
				echo '</div>';
			}

			wp_reset_postdata();
		} else {
			echo '<p>' . esc_html__( 'No posts found.', 'sitsel' ) . '</p>';
			echo '</div>'; // .sitsel-post-grid
		}
	}
}
