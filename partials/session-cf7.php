<?php
/**
 * Partial: Contact Form 7 Embed
 */
defined('ABSPATH') || exit;

if (!class_exists('WPCF7'))
    return;

$pid = get_the_ID();
$form_id = get_post_meta($pid, '_pp_cf7_form_id', true);
$title = get_post_meta($pid, '_pp_cf7_section_title', true);
$subtitle = get_post_meta($pid, '_pp_cf7_section_subtitle', true);
$layout = get_post_meta($pid, '_pp_cf7_layout', true) ?: 'centered';
$info_title = get_post_meta($pid, '_pp_cf7_info_title', true);
$info_content = get_post_meta($pid, '_pp_cf7_info_content', true);
$bg = get_post_meta($pid, '_pp_cf7_bg_color', true) ?: '#f8f8f8';
$anchor_id = get_post_meta($pid, '_pp_cf7_anchor_id', true) ?: 'pp-cf7-form';

if (!$form_id)
    return;
?>
<section class="pp-session pp-cf7" id="<?php echo esc_attr($anchor_id); ?>"">

    <div class=" pp-container pp-cf7-inner pp-cf7-layout-<?php echo esc_attr($layout); ?>">

    <!-- Form column -->
    <div class="pp-cf7-form-col pp-fade-up">
        <?php if ($title): ?>
            <h2 class="pp-cf7-title"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>

        <?php if ($subtitle): ?>
            <p class="pp-cf7-subtitle"><?php echo nl2br(esc_html($subtitle)); ?></p>
        <?php endif; ?>

        <div class="pp-cf7-form-wrap">
            <?php echo do_shortcode('[contact-form-7 id="' . esc_attr($form_id) . '"]'); ?>
        </div>
    </div><!-- .pp-cf7-form-col -->

    <!-- Info column (two-col layout only) -->
    <?php if ($layout === 'two-col' && ($info_title || $info_content)): ?>
        <div class="pp-cf7-info-col pp-fade-up">
            <?php if ($info_title): ?>
                <h3 class="pp-cf7-info-title"><?php echo esc_html($info_title); ?></h3>
            <?php endif; ?>

            <?php if ($info_content): ?>
                <div class="pp-cf7-info-content"><?php echo wp_kses_post($info_content); ?></div>
            <?php endif; ?>
        </div><!-- .pp-cf7-info-col -->
    <?php endif; ?>

    </div><!-- .pp-cf7-inner -->

</section><!-- .pp-cf7 -->