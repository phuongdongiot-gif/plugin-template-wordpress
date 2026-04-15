<?php
/**
 * Partial: Video — Full-width 16:9
 */
defined('ABSPATH') || exit;

$pid = get_the_ID();
$type = get_post_meta($pid, '_pp_video_type', true) ?: 'youtube';
$url = get_post_meta($pid, '_pp_video_url', true);
$poster_id = get_post_meta($pid, '_pp_video_poster', true);
$autoplay = get_post_meta($pid, '_pp_video_autoplay', true);
$watermark_id = get_post_meta($pid, '_pp_video_watermark_logo', true);
$wm_pos = get_post_meta($pid, '_pp_video_watermark_pos', true) ?: 'top-left';

if (!$url)
    return;

$poster_url = $poster_id ? wp_get_attachment_image_url((int) $poster_id, 'full') : '';

// Build embed URL
$embed_url = '';
if ($type === 'youtube') {
    preg_match('~(?:youtube\.com/watch\?v=|youtu\.be/)([^&\s]+)~', $url, $m);
    $vid = $m[1] ?? '';
    if ($vid) {
        $params = 'rel=0&showinfo=0&modestbranding=1';
        $params .= $autoplay ? '&autoplay=1&mute=1' : '';
        $embed_url = "https://www.youtube.com/embed/{$vid}?{$params}";
    }
} elseif ($type === 'vimeo') {
    preg_match('~vimeo\.com/(\d+)~', $url, $m);
    $vid = $m[1] ?? '';
    if ($vid) {
        $params = 'title=0&byline=0&portrait=0';
        $params .= $autoplay ? '&autoplay=1&muted=1' : '';
        $embed_url = "https://player.vimeo.com/video/{$vid}?{$params}";
    }
}
?>
<section class="pp-session pp-video" id="pp-session-video">
    <div class="pp-video-wrapper">

        <?php if ($type === 'self_hosted'): ?>
            <video class="pp-video-self" src="<?php echo esc_url($url); ?>" <?php if ($poster_url)
                     echo 'poster="' . esc_url($poster_url) . '"'; ?>     <?php if ($autoplay)
                                      echo 'autoplay muted playsinline'; ?> controls
                preload="metadata">
            </video>

        <?php elseif ($embed_url): ?>
            <?php if ($poster_url && !$autoplay): ?>
                <!-- Poster overlay — click to load iframe -->
                <div class="pp-video-poster js-video-poster"
                    data-src="<?php echo esc_url($embed_url . '&autoplay=1&mute=1'); ?>"
                    style="background-image:url('<?php echo esc_url($poster_url); ?>');">
                    <button class="pp-play-btn" aria-label="<?php esc_attr_e('Play video', 'part-pages'); ?>">
                        <svg viewBox="0 0 80 80" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="40" cy="40" r="38" fill="rgba(0,0,0,.55)" stroke="#fff" stroke-width="2" />
                            <polygon points="30,22 62,40 30,58" fill="#fff" />
                        </svg>
                    </button>
                </div>
            <?php else: ?>
                <iframe src="<?php echo esc_url($embed_url); ?>" allowfullscreen
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    title="Video">
                </iframe>
            <?php endif; ?>
        <?php endif; ?>

        <!-- <?php if ($watermark_id): ?>
        <div class="pp-video-watermark pp-wm-<?php echo esc_attr(str_replace('-', '_', $wm_pos)); ?>">
            <?php echo wp_get_attachment_image((int) $watermark_id, [120, 60], false, ['alt' => '']); ?>
        </div>
        <?php endif; ?> -->

    </div><!-- .pp-video-wrapper -->
</section><!-- .pp-video -->