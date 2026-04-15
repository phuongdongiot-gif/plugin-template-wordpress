<?php
/**
 * Partial: Hero — Logo + Subtitle + 3-line title
 */
defined( 'ABSPATH' ) || exit;

$pid        = get_the_ID();
$logo_id    = get_post_meta( $pid, '_pp_hero_logo',        true );
$subtitle   = get_post_meta( $pid, '_pp_hero_subtitle',    true );
$line1      = get_post_meta( $pid, '_pp_hero_title_line1', true );
$line2      = get_post_meta( $pid, '_pp_hero_title_line2', true );
$line3      = get_post_meta( $pid, '_pp_hero_title_line3', true );
$size       = get_post_meta( $pid, '_pp_hero_title_size',  true ) ?: 64;
$bg         = get_post_meta( $pid, '_pp_hero_bg_color',    true ) ?: '#fdf5f0';
?>
<section class="pp-session pp-hero" style="background-color:<?php echo esc_attr( $bg ); ?>;" id="pp-session-hero">
    <div class="pp-container pp-hero-inner">

        <?php if ( $logo_id ) : ?>
            <div class="pp-hero-logo pp-fade-up">
                <?php echo wp_get_attachment_image( (int) $logo_id, 'medium', false, [ 'class' => 'pp-logo-img', 'alt' => get_bloginfo( 'name' ) ] ); ?>
            </div>
        <?php endif; ?>

        <?php if ( $subtitle ) : ?>
            <p class="pp-hero-subtitle pp-fade-up"><?php echo esc_html( $subtitle ); ?></p>
        <?php endif; ?>

        <?php if ( $line1 || $line2 || $line3 ) : ?>
            <h1 class="pp-hero-title pp-fade-up" style="font-size:<?php echo esc_attr( $size ); ?>px;">
                <?php if ( $line1 ) : ?><span class="pp-title-line"><?php echo esc_html( $line1 ); ?></span><?php endif; ?>
                <?php if ( $line2 ) : ?><span class="pp-title-line"><?php echo esc_html( $line2 ); ?></span><?php endif; ?>
                <?php if ( $line3 ) : ?><span class="pp-title-line"><?php echo esc_html( $line3 ); ?></span><?php endif; ?>
            </h1>
        <?php endif; ?>

    </div><!-- .pp-hero-inner -->
</section><!-- .pp-hero -->
