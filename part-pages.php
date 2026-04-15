<?php
/**
 * Plugin Name: Part Pages
 * Description: Custom Page Template for Event / Product Launch pages. Drag-and-drop session ordering. Requires Contact Form 7 for the Form session.
 * Version:     1.0.0
 * Author:      Part Pages
 * Text Domain: part-pages
 * Template Name: Part Page – Event / Launch
 */

defined('ABSPATH') || exit;

define('PP_VERSION', '1.0.0');
define('PP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PP_PLUGIN_URL', plugin_dir_url(__FILE__));

// Load class files
require_once PP_PLUGIN_DIR . 'includes/class-meta-boxes.php';
require_once PP_PLUGIN_DIR . 'includes/class-admin-page.php';

/**
 * Main plugin class.
 */
class Part_Pages
{

    /** Template identifier stored in _wp_page_template meta */
    const TEMPLATE_KEY = 'part-pages/templates/page-part-page.php';

    public function __construct()
    {
        // MỚI: Thêm hook này để ép REST API của Block Editor (Gutenberg) phải nhận diện
        add_filter('theme_templates', [$this, 'register_template']);

        // Serve correct file when template is active
        add_filter('template_include', [$this, 'load_template']);
        // Assets
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin']);
        // CF7 dependency notice
        add_action('plugins_loaded', [$this, 'check_cf7']);
    }

    /** Add our template to the Page Attributes dropdown. */
    public function register_template($templates)
    {
        $templates[self::TEMPLATE_KEY] = __('Part Page – Event / Launch', 'part-pages');
        return $templates;
    }

    /** Redirect to plugin template file when the page uses our key. */
    public function load_template($template)
    {
        if (!is_page()) {
            return $template;
        }
        $meta = get_post_meta(get_the_ID(), '_wp_page_template', true);
        if (self::TEMPLATE_KEY === $meta) {
            $plugin_tpl = PP_PLUGIN_DIR . 'templates/page-part-page.php';
            if (file_exists($plugin_tpl)) {
                return $plugin_tpl;
            }
        }
        return $template;
    }

    /** Enqueue frontend styles & scripts only on our template pages. */
    public function enqueue_frontend()
    {
        if (!is_page()) {
            return;
        }
        $meta = get_post_meta(get_the_ID(), '_wp_page_template', true);
        if (self::TEMPLATE_KEY !== $meta) {
            return;
        }

        // Google Fonts
        wp_enqueue_style(
            'pp-google-fonts',
            'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap',
            [],
            null
        );

        wp_enqueue_style(
            'pp-front',
            PP_PLUGIN_URL . 'assets/css/part-page-front.css',
            ['pp-google-fonts'],
            PP_VERSION
        );

        wp_enqueue_script(
            'pp-front',
            PP_PLUGIN_URL . 'assets/js/part-page-front.js',
            ['jquery'],
            PP_VERSION,
            true
        );
    }

    /** Enqueue admin assets only on Page edit screens. */
    public function enqueue_admin($hook)
    {
        if (!in_array($hook, ['post.php', 'post-new.php'], true)) {
            return;
        }
        global $post;
        if (!$post || $post->post_type !== 'page') {
            return;
        }

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_media();

        wp_enqueue_style(
            'pp-admin',
            PP_PLUGIN_URL . 'assets/css/part-page-admin.css',
            ['wp-color-picker'],
            PP_VERSION
        );

        wp_enqueue_script(
            'pp-admin',
            PP_PLUGIN_URL . 'assets/js/part-page-admin.js',
            ['jquery', 'jquery-ui-sortable', 'wp-color-picker'],
            PP_VERSION,
            true
        );

        wp_localize_script('pp-admin', 'pp_admin', [
            'nonce' => wp_create_nonce('pp_admin_nonce'),
            'template_key' => self::TEMPLATE_KEY,
            'upload_title' => __('Chọn ảnh', 'part-pages'),
            'upload_btn' => __('Dùng ảnh này', 'part-pages'),
        ]);
    }

    /** Show admin notice when CF7 is not active. */
    public function check_cf7()
    {
        if (!is_admin()) {
            return;
        }
        if (!class_exists('WPCF7')) {
            add_action('admin_notices', function () {
                $url = admin_url('plugin-install.php?s=contact+form+7&tab=search&type=term');
                echo '<div class="notice notice-warning is-dismissible"><p>';
                printf(
                    wp_kses(
                        __('<strong>Part Pages:</strong> <a href="%s">Contact Form 7</a> chưa được cài đặt — Session "Contact Form 7" sẽ bị ẩn cho đến khi kích hoạt plugin đó.', 'part-pages'),
                        ['strong' => [], 'a' => ['href' => []]]
                    ),
                    esc_url($url)
                );
                echo '</p></div>';
            });
        }
    }
}

new Part_Pages();
new PP_Meta_Boxes();
new PP_Admin_Page();
