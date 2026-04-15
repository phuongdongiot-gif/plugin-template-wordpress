<?php
/**
 * Partial: Text Section — large title + rich paragraphs
 */
defined( 'ABSPATH' ) || exit;

$pid      = get_the_ID();
$title    = get_post_meta( $pid, '_pp_text_title',      true );
$font     = get_post_meta( $pid, '_pp_text_title_font', true ) ?: 'serif';
$size     = get_post_meta( $pid, '_pp_text_title_size', true ) ?: 48;
$content  = get_post_meta( $pid, '_pp_text_content',    true );
$align    = get_post_meta( $pid, '_pp_text_align',      true ) ?: 'center';
$maxw     = get_post_meta( $pid, '_pp_text_max_width',  true ) ?: 800;
$bg       = get_post_meta( $pid, '_pp_text_bg_color',   true ) ?: '#ffffff';
$color    = get_post_meta( $pid, '_pp_text_text_color', true ) ?: '#1a1a1a';

if ( ! $title && ! $content ) return;

$font_class = ( $font === 'serif' ) ? 'pp-font-serif' : 'pp-font-sans';
?>
<section class="pp-session pp-text-section" id="pp-session-text"
         style="background-color:<?php echo esc_attr( $bg ); ?>; color:<?php echo esc_attr( $color ); ?>;">

    <div class="pp-container pp-text-inner pp-text-align-<?php echo esc_attr( $align ); ?>"
         style="max-width:<?php echo esc_attr( $maxw ); ?>px;">

        <?php if ( $title ) : ?>
            <h2 class="pp-text-title <?php echo esc_attr( $font_class ); ?> pp-fade-up"
                style="font-size:<?php echo esc_attr( $size ); ?>px; color:<?php echo esc_attr( $color ); ?>;">
                <?php echo esc_html( $title ); ?>
            </h2>
        <?php endif; ?>

        <?php if ( $content ) : ?>
            <div class="pp-text-content pp-fade-up">
                <?php echo wp_kses_post( $content ); ?>
            </div>
        <?php endif; ?>

    </div><!-- .pp-text-inner -->

</section><!-- .pp-text-section -->
