<?php
/**
 * Partial: CTA Banner — background image + overlay + heading + button
 */
defined('ABSPATH') || exit;

$pid = get_the_ID();
$bg_id = get_post_meta($pid, '_pp_banner_bg_image', true);
$ov_color = get_post_meta($pid, '_pp_banner_overlay_color', true) ?: '#000000';
$ov_opacity = get_post_meta($pid, '_pp_banner_overlay_opacity', true);
$ov_opacity = ($ov_opacity !== '') ? (int) $ov_opacity / 100 : 0.5;
$heading = get_post_meta($pid, '_pp_banner_heading', true);
$hfont = get_post_meta($pid, '_pp_banner_heading_font', true) ?: 'serif';
$subtext = get_post_meta($pid, '_pp_banner_subtext', true);
$btn_txt = get_post_meta($pid, '_pp_banner_button_text', true);
$btn_act = get_post_meta($pid, '_pp_banner_button_action', true) ?: 'scroll_to_cf7';
$btn_url = get_post_meta($pid, '_pp_banner_button_url', true);
$btn_style = get_post_meta($pid, '_pp_banner_button_style', true) ?: 'outline-white';

// Resolve button href
if ($btn_act === 'scroll_to_cf7') {
    $cf7_anchor = get_post_meta($pid, '_pp_cf7_anchor_id', true) ?: 'pp-cf7-form';
    $btn_href = '#' . esc_attr($cf7_anchor);
} elseif ($btn_act === 'scroll_to_event') {
    $btn_href = '#pp-session-event';
} elseif ($btn_act === 'external_url' && $btn_url) {
    $btn_href = esc_url($btn_url);
} else {
    $btn_href = '#pp-cf7-form';
}

$bg_url = $bg_id ? wp_get_attachment_image_url((int) $bg_id, 'full') : '';

// Build hex→rgba overlay
list($r, $g, $b) = sscanf($ov_color, '#%02x%02x%02x');
$overlay_rgba = "rgba({$r},{$g},{$b},{$ov_opacity})";

$font_class = ($hfont === 'serif') ? 'pp-font-serif' : 'pp-font-sans';
?>
<section class="pp-session pp-banner" id="pp-session-banner">


    <div class="pp-container pp-banner-inner pp-fade-up" <?php if ($bg_url)
        echo 'style="background-image:url(\'' . esc_url($bg_url) . '\')"'; ?>>
        <!-- Dark overlay -->
        <!-- <div class="pp-banner-overlay" style="background-color:<?php echo esc_attr($overlay_rgba); ?>;"></div> -->

        <?php if ($heading): ?>
            <h2 class="pp-banner-heading <?php echo esc_attr($font_class); ?>">
                <?php echo esc_html($heading); ?>
            </h2>
        <?php endif; ?>

        <?php if ($subtext): ?>
            <p class="pp-banner-subtext"><?php echo nl2br(esc_html($subtext)); ?></p>
        <?php endif; ?>

        <?php if ($btn_txt): ?>
            <a href="<?php echo esc_attr($btn_href); ?>"
                class="pp-btn pp-btn-<?php echo esc_attr($btn_style); ?><?php echo in_array($btn_act, ['scroll_to_cf7', 'scroll_to_event'], true) ? ' pp-scroll-btn' : ''; ?>"
                <?php if ($btn_act === 'external_url')
                    echo 'target="_blank" rel="noopener"'; ?>>
                <?php echo esc_html($btn_txt); ?>
            </a>
        <?php endif; ?>

    </div><!-- .pp-banner-inner -->

</section><!-- .pp-banner -->