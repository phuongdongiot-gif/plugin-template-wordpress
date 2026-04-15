<?php
/**
 * Template Name: Part Page – Event / Launch
 * Template Post Type: page
 *
 * Renders sessions in the admin-defined drag-drop order.
 */

defined('ABSPATH') || exit;

get_header();

$pid = get_the_ID();
$raw_order = get_post_meta($pid, '_pp_session_order', true);
$default_order = ['hero', 'video', 'event', 'tabs', 'text', 'banner', 'cf7'];
$session_order = $raw_order ? json_decode($raw_order, true) : $default_order;

if (!is_array($session_order)) {
    $session_order = $default_order;
}

$partials_dir = plugin_dir_path(dirname(__FILE__)) . 'partials/';

foreach ($session_order as $session_key) {
    $session_key = sanitize_key($session_key);
    $enabled = get_post_meta($pid, "_pp_{$session_key}_enabled", true);

    // Default to enabled if meta not yet saved
    if ($enabled === '') {
        $enabled = '1';
    }

    if ($enabled !== '1') {
        continue;
    }

    $partial_file = $partials_dir . "session-{$session_key}.php";

    // Thêm đoạn này để bắt ngoại lệ riêng cho thằng cf7
    if ($session_key === 'cf7') {
        $partial_file = $partials_dir . "session-cf7-form.php";
    }

    // Only load partials that exist to prevent fatal errors
    if (file_exists($partial_file)) {
        include $partial_file;
    }
}

get_footer();
