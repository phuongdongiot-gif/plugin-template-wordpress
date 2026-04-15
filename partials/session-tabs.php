<?php
/**
 * Partial: Tabbed Content — up to 7 tabs
 */
defined( 'ABSPATH' ) || exit;

$pid      = get_the_ID();
$subtitle = get_post_meta( $pid, '_pp_tabs_subtitle',     true );
$ac_color = get_post_meta( $pid, '_pp_tabs_active_color', true ) ?: '#8B2A2A';
$tabs_json = get_post_meta( $pid, '_pp_tabs_items',       true );
$tabs     = $tabs_json ? json_decode( $tabs_json, true ) : [];

if ( empty( $tabs ) ) return;
?>
<section class="pp-session pp-tabs" id="pp-session-tabs"
         style="--pp-tab-active:<?php echo esc_attr( $ac_color ); ?>;">

    <?php if ( $subtitle ) : ?>
        <p class="pp-tabs-subtitle pp-container"><?php echo esc_html( $subtitle ); ?></p>
    <?php endif; ?>

    <!-- Tab navigation -->
    <div class="pp-tabs-nav-wrap" id="pp-tabs-nav-wrap">
        <div class="pp-container">
            <ul class="pp-tabs-nav" role="tablist" id="pp-tabs-nav">
                <?php foreach ( $tabs as $i => $tab ) :
                    if ( empty( $tab['label'] ) ) continue;
                    $is_active = ( $i === 0 );
                ?>
                    <li role="presentation">
                        <button class="pp-tab-btn<?php echo $is_active ? ' is-active' : ''; ?>"
                                role="tab"
                                aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                                aria-controls="pp-tab-panel-<?php echo esc_attr( $i ); ?>"
                                id="pp-tab-<?php echo esc_attr( $i ); ?>"
                                data-tab="<?php echo esc_attr( $i ); ?>">
                            <?php echo esc_html( $tab['label'] ); ?>
                        </button>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div><!-- .pp-tabs-nav-wrap -->

    <!-- Tab panels -->
    <div class="pp-tabs-panels pp-container">
        <?php foreach ( $tabs as $i => $tab ) :
            $is_active = ( $i === 0 );
        ?>
            <div class="pp-tab-panel<?php echo $is_active ? ' is-active' : ''; ?>"
                 role="tabpanel"
                 id="pp-tab-panel-<?php echo esc_attr( $i ); ?>"
                 aria-labelledby="pp-tab-<?php echo esc_attr( $i ); ?>"
                 <?php echo $is_active ? '' : 'hidden'; ?>>

                <div class="pp-tab-inner<?php echo ! empty( $tab['image_url'] ) ? ' has-image' : ''; ?>">

                    <div class="pp-tab-content-col">
                        <?php if ( ! empty( $tab['title'] ) ) : ?>
                            <h3 class="pp-tab-title"><?php echo esc_html( $tab['title'] ); ?></h3>
                        <?php endif; ?>

                        <?php if ( ! empty( $tab['intro'] ) ) : ?>
                            <p class="pp-tab-intro"><?php echo esc_html( $tab['intro'] ); ?></p>
                        <?php endif; ?>

                        <?php if ( ! empty( $tab['content'] ) ) : ?>
                            <div class="pp-tab-body"><?php echo wp_kses_post( $tab['content'] ); ?></div>
                        <?php endif; ?>

                        <?php if ( ! empty( $tab['button_text'] ) && ! empty( $tab['button_url'] ) ) : ?>
                            <a href="<?php echo esc_url( $tab['button_url'] ); ?>"
                               class="pp-btn pp-btn-tab"
                               target="_blank" rel="noopener">
                                <?php echo esc_html( $tab['button_text'] ); ?>
                            </a>
                        <?php endif; ?>
                    </div><!-- .pp-tab-content-col -->

                    <?php if ( ! empty( $tab['image_url'] ) ) : ?>
                        <div class="pp-tab-image-col">
                            <img src="<?php echo esc_url( $tab['image_url'] ); ?>"
                                 alt="<?php echo esc_attr( $tab['title'] ); ?>"
                                 loading="lazy">
                        </div>
                    <?php endif; ?>

                </div><!-- .pp-tab-inner -->

            </div><!-- .pp-tab-panel -->
        <?php endforeach; ?>
    </div><!-- .pp-tabs-panels -->

</section><!-- .pp-tabs -->
