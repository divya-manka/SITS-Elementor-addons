<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;

class Sitsel_Post_Grid_Widget extends Widget_Base {

    public function get_name() {
        return 'sitsel_post_grid';
    }

    public function get_title() {
        return __( 'Sitsel Post Grid', 'sits-elementor-addons' );
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return [ 'sits-category' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'sitsel_content_section',
            [
                'label' => __( 'Query Settings', 'sits-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $post_types = get_post_types( [ 'public' => true ], 'objects' );
        $options = [];

        foreach ( $post_types as $post_type ) {
            $options[ $post_type->name ] = $post_type->label;
        }

        $this->add_control(
            'sitsel_post_type',
            [
                'label' => __( 'Post Type', 'sits-elementor-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'post',
                'options' => $options,
            ]
        );

        $this->add_control(
            'sitsel_posts_per_page',
            [
                'label' => __( 'Posts Per Page', 'sits-elementor-addons' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );

        $this->end_controls_section();

        // Template Selection
        $this->start_controls_section(
            'sitsel_template_section',
            [
                'label' => __( 'Layout Template', 'sits-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $templates = get_posts([
            'post_type' => 'elementor_library',
            'posts_per_page' => -1,
        ]);

        $template_options = [];
        foreach ( $templates as $template ) {
            $template_options[ $template->ID ] = $template->post_title;
        }

        $this->add_control(
            'sitsel_template_id',
            [
                'label' => __( 'Select Template', 'sits-elementor-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => $template_options,
                'default' => '',
            ]
        );

        $this->add_control(
            'sitsel_edit_template_link',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<a id="sitsel-template-edit-link" class="elementor-button elementor-button-default" target="_blank" style="margin-top:10px;display:inline-block;">Edit Template</a>',
                'content_classes' => 'sitsel-edit-template-control',
                'separator' => 'none',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $query = new WP_Query([
            'post_type' => $settings['sitsel_post_type'],
            'posts_per_page' => $settings['sitsel_posts_per_page'],
        ]);

        echo '<div class="sitsel-post-grid">';

        if ( $query->have_posts() && ! empty( $settings['sitsel_template_id'] ) ) {
            while ( $query->have_posts() ) {
                $query->the_post();

                // Temporarily override global post for template rendering
                global $post;
                $original_post = $post;
                $post = get_post();
                setup_postdata( $post );

                echo '<div class="sitsel-post-item">';
                echo Plugin::instance()->frontend->get_builder_content_for_display( $settings['sitsel_template_id'] );
                echo '</div>';

                $post = $original_post;
                wp_reset_postdata();
            }
        } else {
            echo '<p>No posts found or no template selected.</p>';
        }

        echo '</div>';
    }

    // Add dynamic JavaScript to update the "Edit Template" link
    public function get_script_depends() {
    return [ 'sitsel-post-grid-js' ];
}

    public function _content_template() {}
}
add_action( 'elementor/editor/after_enqueue_scripts', function() {
    ?>
    <script>
    jQuery(function($) {
        setInterval(function () {
            let templateId = $('select[name="sitsel_template_id"]').val();
            let $editBtn = $('#sitsel-template-edit-link');
            if (templateId) {
                $editBtn.attr('href', '/wp-admin/post.php?post=' + templateId + '&action=elementor').show();
            } else {
                $editBtn.hide();
            }
        }, 1000);
    });
    </script>
    <?php
});
