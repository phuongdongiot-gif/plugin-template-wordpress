<?php
/**
 * Partial: Event Card + Time & Location + About
 */
defined( 'ABSPATH' ) || exit;

$pid     = get_the_ID();
$img_id  = get_post_meta( $pid, '_pp_event_image',         true );
$img_pos = get_post_meta( $pid, '_pp_event_image_position',true ) ?: 'left';
$date    = get_post_meta( $pid, '_pp_event_date_display',  true );
$loc     = get_post_meta( $pid, '_pp_event_location_short',true );
$title   = get_post_meta( $pid, '_pp_event_title',         true );
$desc    = get_post_meta( $pid, '_pp_event_description',   true );
$btn_txt = get_post_meta( $pid, '_pp_event_button_text',   true );
$btn_act = get_post_meta( $pid, '_pp_event_button_action', true ) ?: 'scroll_to_cf7';
$btn_url = get_post_meta( $pid, '_pp_event_button_url',    true );

$show_tl  = get_post_meta( $pid, '_pp_event_show_time_location', true );
$datetime = get_post_meta( $pid, '_pp_event_full_datetime', true );
$address  = get_post_meta( $pid, '_pp_event_full_address',  true );
$maps_url = get_post_meta( $pid, '_pp_event_maps_url',      true );

$show_about    = get_post_meta( $pid, '_pp_event_show_about',         true );
$about_logo_id = get_post_meta( $pid, '_pp_event_about_logo',         true );
$about_content = get_post_meta( $pid, '_pp_event_about_content',      true );
$about_img_id  = get_post_meta( $pid, '_pp_event_about_product_image',true );
$about_img_pos = get_post_meta( $pid, '_pp_event_about_image_pos',    true ) ?: 'right';

// Button href
if ( $btn_act === 'scroll_to_cf7' ) {
    $cf7_anchor = get_post_meta( $pid, '_pp_cf7_anchor_id', true ) ?: 'pp-cf7-form';
    $btn_href   = '#' . esc_attr( $cf7_anchor );
} elseif ( $btn_act === 'external_url' && $btn_url ) {
    $btn_href = esc_url( $btn_url );
} else {
    $btn_href = '#pp-cf7-form';
}
?>
<section class="pp-session pp-event" id="pp-session-event">

    <!-- ─── A: 2-column Card ─── -->
    <div class="pp-event-card pp-event-img-<?php echo esc_attr( $img_pos ); ?>">

        <?php if ( $img_id ) : ?>
            <div class="pp-event-image pp-fade-up">
                <?php echo wp_get_attachment_image( (int) $img_id, 'large', false, [ 'class' => 'pp-event-img', 'alt' => esc_attr( $title ) ] ); ?>
            </div>
        <?php endif; ?>

        <div class="pp-event-info pp-fade-up">
            <?php if ( $date || $loc ) : ?>
                <p class="pp-event-meta">
                    <?php if ( $date ) echo '<span class="pp-event-date">' . esc_html( $date ) . '</span>'; ?>
                    <?php if ( $date && $loc ) echo ' &nbsp;|&nbsp; '; ?>
                    <?php if ( $loc )  echo '<span class="pp-event-loc">'  . esc_html( $loc )  . '</span>'; ?>
                </p>
            <?php endif; ?>

            <?php if ( $title ) : ?>
                <h2 class="pp-event-title"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>

            <?php if ( $desc ) : ?>
                <div class="pp-event-desc"><?php echo wp_kses_post( nl2br( esc_html( $desc ) ) ); ?></div>
            <?php endif; ?>

            <?php if ( $btn_txt ) : ?>
                <a href="<?php echo esc_attr( $btn_href ); ?>"
                   class="pp-btn pp-btn-event<?php echo $btn_act === 'external_url' ? '' : ' pp-scroll-btn'; ?>"
                   <?php if ( $btn_act === 'external_url' ) echo 'target="_blank" rel="noopener"'; ?>>
                    <?php echo esc_html( $btn_txt ); ?>
                </a>
            <?php endif; ?>
        </div><!-- .pp-event-info -->

    </div><!-- .pp-event-card -->

    <!-- ─── B: Time & Location ─── -->
    <?php if ( $show_tl === '1' && ( $datetime || $address ) ) : ?>
        <div class="pp-event-tl">
            <div class="pp-container">
                <h3 class="pp-tl-heading">Time and Location</h3>
                <?php if ( $datetime ) : ?>
                    <p class="pp-tl-datetime"><?php echo esc_html( $datetime ); ?></p>
                <?php endif; ?>
                <?php if ( $address ) : ?>
                    <p class="pp-tl-address">
                        <?php echo esc_html( $address ); ?>
                        <!-- <?php if ( $maps_url ) : ?>
                            <a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener" class="pp-maps-link">
                                <?php esc_html_e( '(View on Maps)', 'part-pages' ); ?>
                            </a>
                        <?php endif; ?> -->
                    </p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>


    <!-- ─── C: About ─── -->
    <?php if ( $show_about === '1' ) : ?>
        <div class="pp-event-about pp-container pp-fade-up">
            <h3 class="pp-about-heading"><?php esc_html_e( 'About', 'part-pages' ); ?></h3>

            <div class="pp-about-inner pp-about-img-<?php echo esc_attr( $about_img_pos ); ?>">
                <div class="pp-about-content">
                    <?php if ( $about_logo_id ) : ?>
                        <div class="pp-about-logo">
                            <?php echo wp_get_attachment_image( (int) $about_logo_id, 'medium', false, [ 'class' => 'pp-about-logo-img', 'alt' => '' ] ); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ( $about_content ) : ?>
                        <div class="pp-about-text"><?php echo wp_kses_post( $about_content ); ?></div>
                    <?php endif; ?>
                </div>

                <?php if ( $about_img_id && $about_img_pos !== 'none' ) : ?>
                    <div class="pp-about-image">
                        <?php echo wp_get_attachment_image( (int) $about_img_id, 'large', false, [ 'class' => 'pp-about-product-img', 'alt' => '' ] ); ?>
                    </div>
                <?php endif; ?>
            </div><!-- .pp-about-inner -->
        </div><!-- .pp-event-about -->
    <?php endif; ?>

</section><!-- .pp-event -->
