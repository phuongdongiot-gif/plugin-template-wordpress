<?php
defined( 'ABSPATH' ) || exit;

/**
 * Registers and handles all Part Page admin meta boxes.
 */
class PP_Meta_Boxes {

    const TEMPLATE_KEY = 'part-pages/templates/page-part-page.php';

    public function __construct() {
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
        add_action( 'save_post',      [ $this, 'save_meta_boxes' ], 10, 2 );
    }

    /**
     * Lấy schema cấu hình động cho các session.
     */
    private function get_schema() {
        $cf7_options = [ '' => '— Chọn form —' ];
        if ( class_exists( 'WPCF7' ) ) {
            $forms = get_posts( [
                'post_type'      => 'wpcf7_contact_form',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
            ] );
            foreach ( $forms as $f ) {
                $cf7_options[ $f->ID ] = "[{$f->ID}] {$f->post_title}";
            }
        }

        $schema = [
            'hero'   => [
                'label'  => '🖼 Hero — Logo + Tiêu đề',
                'fields' => [
                    'hero_logo'        => [ 'type' => 'image', 'label' => 'Logo / Brand Image' ],
                    'hero_subtitle'    => [ 'type' => 'text',  'label' => 'Text nhỏ dưới logo', 'placeholder' => 'vd: Q-SWITCHED ND: YAG LASER' ],
                    'hero_title_line1' => [ 'type' => 'text',  'label' => 'Tiêu đề — Dòng 1' ],
                    'hero_title_line2' => [ 'type' => 'text',  'label' => 'Tiêu đề — Dòng 2' ],
                    'hero_title_line3' => [ 'type' => 'text',  'label' => 'Tiêu đề — Dòng 3' ],
                    'hero_title_size'  => [ 'type' => 'number','label' => 'Cỡ chữ tiêu đề (px)', 'default' => 64 ],
                    'hero_bg_color'    => [ 'type' => 'color', 'label' => 'Màu nền', 'default' => '#fdf5f0' ],
                ],
            ],
            'video'  => [
                'label'  => '🎬 Video Full-width',
                'fields' => [
                    'video_type'          => [ 'type' => 'radio', 'label' => 'Loại video', 'options' => [ 'youtube' => 'YouTube', 'vimeo' => 'Vimeo', 'self_hosted' => 'Self-hosted (MP4)' ], 'default' => 'youtube' ],
                    'video_url'           => [ 'type' => 'text',  'label' => 'URL Video', 'placeholder' => 'vd: https://youtu.be/xxxxx' ],
                    'video_poster'        => [ 'type' => 'image', 'label' => 'Thumbnail (hiện trước khi play)' ],
                    'video_autoplay'      => [ 'type' => 'check', 'label' => 'Autoplay khi vào viewport (muted)' ],
                    'video_watermark_logo'=> [ 'type' => 'image', 'label' => 'Logo Watermark trên video' ],
                    'video_watermark_pos' => [ 'type' => 'radio', 'label' => 'Vị trí watermark', 'options' => [ 'top-left' => 'Trên trái', 'top-right' => 'Trên phải', 'bottom-left' => 'Dưới trái' ], 'default' => 'top-left' ],
                ],
            ],
            'event'  => [
                'label'  => '📅 Event Card',
                'fields' => [
                    'event_heading_a'          => [ 'type' => 'heading', 'label' => 'A — Card chính (2 cột)' ],
                    'event_image'              => [ 'type' => 'image',   'label' => 'Hình ảnh' ],
                    'event_image_position'     => [ 'type' => 'radio',   'label' => 'Vị trí ảnh', 'options' => [ 'left' => 'Trái', 'right' => 'Phải' ], 'default' => 'left' ],
                    'event_date_display'       => [ 'type' => 'text',    'label' => 'Ngày ngắn', 'placeholder' => 'vd: Mon, 30 Mar' ],
                    'event_location_short'     => [ 'type' => 'text',    'label' => 'Địa điểm ngắn', 'placeholder' => 'vd: TADLI Head Office' ],
                    'event_title'              => [ 'type' => 'text',    'label' => 'Tiêu đề sự kiện' ],
                    'event_description'        => [ 'type' => 'editor',  'label' => 'Mô tả' ],
                    'event_button_text'        => [ 'type' => 'text',    'label' => 'Text nút CTA', 'placeholder' => 'vd: Register Now' ],
                    'event_button_action'      => [ 'type' => 'radio',   'label' => 'Hành động nút', 'options' => [ 'scroll_to_cf7' => 'Scroll → Form CF7', 'external_url' => 'Mở URL ngoài' ], 'default' => 'scroll_to_cf7' ],
                    'event_button_url'         => [ 'type' => 'text',    'label' => 'URL ngoài (nếu chọn URL)', 'placeholder' => 'https://' ],

                    'event_heading_b'          => [ 'type' => 'heading', 'label' => 'B — Time & Location (bên dưới card)' ],
                    'event_show_time_location' => [ 'type' => 'check',   'label' => 'Hiển thị phần Time & Location' ],
                    'event_full_datetime'      => [ 'type' => 'text',    'label' => 'Date & Time đầy đủ', 'placeholder' => 'vd: 30 Mar 2026, 5:30 pm – 9:30 pm AEDT' ],
                    'event_full_address'       => [ 'type' => 'text',    'label' => 'Địa chỉ đầy đủ' ],
                    'event_maps_url'           => [ 'type' => 'text',    'label' => 'Link Google Maps (optional)', 'placeholder' => 'https://maps.google.com/...' ],

                    'event_heading_c'          => [ 'type' => 'heading', 'label' => 'C — About (bên dưới Time & Location)' ],
                    'event_show_about'         => [ 'type' => 'check',   'label' => 'Hiển thị phần About' ],
                    'event_about_logo'         => [ 'type' => 'image',   'label' => 'Brand Logo lớn (trong About)' ],
                    'event_about_content'      => [ 'type' => 'editor',  'label' => 'Nội dung About' ],
                    'event_about_product_image'=> [ 'type' => 'image',   'label' => 'Ảnh sản phẩm' ],
                    'event_about_image_pos'    => [ 'type' => 'radio',   'label' => 'Vị trí ảnh sản phẩm', 'options' => [ 'right' => 'Phải', 'left' => 'Trái', 'none' => 'Ẩn' ], 'default' => 'right' ],
                ],
            ],
            'tabs'   => [
                'label'  => '🗂 Tabbed Content',
                'fields' => [
                    'tabs_subtitle'     => [ 'type' => 'text',  'label' => 'Subtitle trên tab bar', 'placeholder' => 'vd: Q-SWITCHED ND: YAG LASER' ],
                    'tabs_active_color' => [ 'type' => 'color', 'label' => 'Màu tab active', 'default' => '#8B2A2A', 'full_width' => true ],
                    'tabs_builder'      => [ 'type' => 'custom', 'callback' => [ $this, 'render_tabs_builder' ] ],
                ],
            ],
            'text'   => [
                'label'  => '📝 Text Section',
                'fields' => [
                    'text_title'      => [ 'type' => 'text',   'label' => 'Tiêu đề lớn' ],
                    'text_title_font' => [ 'type' => 'radio',  'label' => 'Font tiêu đề', 'options' => [ 'serif' => 'Serif (Playfair)', 'sans' => 'Sans-serif (Montserrat)' ], 'default' => 'serif' ],
                    'text_title_size' => [ 'type' => 'number', 'label' => 'Cỡ chữ tiêu đề (px)', 'default' => 48 ],
                    'text_content'    => [ 'type' => 'editor', 'label' => 'Nội dung (nhiều đoạn)' ],
                    'text_align'      => [ 'type' => 'radio',  'label' => 'Căn lề', 'options' => [ 'center' => 'Giữa', 'left' => 'Trái' ], 'default' => 'center' ],
                    'text_max_width'  => [ 'type' => 'number', 'label' => 'Max-width vùng text (px)', 'default' => 800 ],
                    'text_bg_color'   => [ 'type' => 'color',  'label' => 'Màu nền', 'default' => '#ffffff' ],
                    'text_text_color' => [ 'type' => 'color',  'label' => 'Màu chữ', 'default' => '#1a1a1a' ],
                ],
            ],
            'banner' => [
                'label'  => '📢 CTA Banner',
                'fields' => [
                    'banner_bg_image'        => [ 'type' => 'image',   'label' => 'Ảnh nền' ],
                    'banner_overlay_color'   => [ 'type' => 'color',   'label' => 'Màu overlay', 'default' => '#000000' ],
                    'banner_overlay_opacity' => [ 'type' => 'number',  'label' => 'Độ mờ overlay (0–100)', 'default' => 50 ],
                    'banner_heading'         => [ 'type' => 'text',    'label' => 'Heading nổi', 'placeholder' => 'vd: Interested in this device?' ],
                    'banner_heading_font'    => [ 'type' => 'radio',   'label' => 'Font heading', 'options' => [ 'serif' => 'Serif', 'sans' => 'Sans-serif' ], 'default' => 'serif' ],
                    'banner_subtext'         => [ 'type' => 'textarea','label' => 'Mô tả phụ' ],
                    'banner_button_text'     => [ 'type' => 'text',    'label' => 'Text button', 'placeholder' => 'vd: Enquire Now' ],
                    'banner_button_action'   => [ 'type' => 'radio',   'label' => 'Hành động button', 'options' => [ 'scroll_to_cf7' => 'Scroll → Form CF7', 'scroll_to_event' => 'Scroll → Event Card', 'external_url' => 'Mở URL ngoài' ], 'default' => 'scroll_to_cf7' ],
                    'banner_button_url'      => [ 'type' => 'text',    'label' => 'URL ngoài', 'placeholder' => 'https://' ],
                    'banner_button_style'    => [ 'type' => 'radio',   'label' => 'Style button', 'options' => [ 'outline-white' => 'Outline trắng', 'filled-white' => 'Filled trắng', 'filled-accent' => 'Filled accent' ], 'default' => 'outline-white' ],
                ],
            ],
            'cf7'    => [
                'label'  => '📋 Contact Form 7',
                'notice' => class_exists( 'WPCF7' ) ? '' : 'Contact Form 7 chưa được kích hoạt. Hãy cài đặt plugin đó để dùng session này.',
                'fields' => [
                    'cf7_form_id'          => [ 'type' => 'select',  'label' => 'CF7 Form', 'options' => $cf7_options ],
                    'cf7_section_title'    => [ 'type' => 'text',    'label' => 'Tiêu đề section', 'placeholder' => 'vd: DEVICE CONTACT FORM' ],
                    'cf7_section_subtitle' => [ 'type' => 'textarea','label' => 'Mô tả ngắn' ],
                    'cf7_layout'           => [ 'type' => 'radio',   'label' => 'Layout', 'options' => [ 'centered' => '1 cột (căn giữa)', 'two-col' => '2 cột (form trái + info phải)' ], 'default' => 'centered' ],
                    'cf7_info_title'       => [ 'type' => 'text',    'label' => 'Tiêu đề cột phải (nếu 2 cột)', 'default' => 'CÁC TRƯỜNG YÊU CẦU ĐIỀN' ],
                    'cf7_info_content'     => [ 'type' => 'editor',  'label' => 'Nội dung cột phải' ],
                    'cf7_bg_color'         => [ 'type' => 'color',   'label' => 'Màu nền section', 'default' => '#f8f8f8' ],
                    'cf7_anchor_id'        => [ 'type' => 'text',    'label' => 'HTML Anchor ID', 'default' => 'pp-cf7-form' ],
                ],
            ],
        ];

        return apply_filters( 'pp_registered_sessions', $schema );
    }

    public function add_meta_boxes() {
        add_meta_box(
            'pp_sessions',
            __( 'Part Page Sessions', 'part-pages' ),
            [ $this, 'render_meta_box' ],
            'page',
            'normal',
            'high'
        );
    }

    public function render_meta_box( $post ) {
        $tpl = get_post_meta( $post->ID, '_wp_page_template', true );

        if ( $tpl !== self::TEMPLATE_KEY ) {
            echo '<div class="pp-inactive-notice">';
            echo '<span class="dashicons dashicons-info-outline"></span> ';
            esc_html_e( 'Chọn template "Part Page – Event / Launch" trong Page Attributes để mở rộng các fields bên dưới.', 'part-pages' );
            echo '</div>';
            return;
        }

        wp_nonce_field( 'pp_save_sessions', 'pp_sessions_nonce' );

        $schema = $this->get_schema();
        
        $raw_order = get_post_meta( $post->ID, '_pp_session_order', true );
        $order     = $raw_order ? json_decode( $raw_order, true ) : array_keys( $schema );

        if ( ! is_array( $order ) ) {
            $order = array_keys( $schema );
        }

        foreach ( array_keys( $schema ) as $k ) {
            if ( ! in_array( $k, $order, true ) ) {
                $order[] = $k;
            }
        }

        echo '<div class="pp-sessions-wrap">';
        echo '<p class="pp-drag-hint"><span class="dashicons dashicons-move"></span> ';
        esc_html_e( 'Kéo ⠿ để sắp xếp thứ tự · Tick checkbox để bật/tắt session · Click tiêu đề để mở form', 'part-pages' );
        echo '</p>';
        echo '<ul class="pp-sortable" id="pp-sessions-sortable">';

        foreach ( $order as $key ) {
            if ( ! isset( $schema[ $key ] ) ) continue;
            $this->render_session_row( $post, $key, $schema[ $key ] );
        }

        echo '</ul>';
        echo '<input type="hidden" name="pp_session_order" id="pp-session-order" value="' . esc_attr( wp_json_encode( $order ) ) . '">';
        echo '</div>';
    }

    private function render_session_row( $post, $key, $session_config ) {
        $enabled = get_post_meta( $post->ID, "_pp_{$key}_enabled", true );
        $enabled = ( $enabled === '' ) ? '1' : $enabled;
        $is_on   = ( $enabled === '1' );
        $label   = $session_config['label'];
        ?>
        <li class="pp-session-row<?php echo $is_on ? ' is-enabled' : ''; ?>" data-key="<?php echo esc_attr( $key ); ?>">
            <div class="pp-row-header">
                <span class="pp-drag-handle dashicons dashicons-move" title="Kéo để thay đổi thứ tự"></span>

                <label class="pp-toggle" title="Bật/tắt session">
                    <input type="checkbox"
                           name="pp_<?php echo esc_attr( $key ); ?>_enabled"
                           value="1"
                           <?php checked( $is_on ); ?>>
                    <span class="pp-toggle-track"></span>
                </label>

                <span class="pp-row-label"><?php echo esc_html( $label ); ?></span>

                <span class="pp-row-arrow dashicons dashicons-arrow-down-alt2"></span>
            </div>

            <div class="pp-row-body">
                <?php if ( ! empty( $session_config['notice'] ) ) : ?>
                    <div class="pp-notice-warning"><span class="dashicons dashicons-warning"></span> 
                        <?php echo esc_html( $session_config['notice'] ); ?>
                    </div>
                <?php endif; ?>
                
                <div class="pp-fields-grid">
                    <?php
                    foreach ( $session_config['fields'] as $field_key => $field_config ) {
                        $this->render_field( $post->ID, $field_key, $field_config );
                    }
                    ?>
                </div>
            </div>
        </li>
        <?php
    }

    private function render_field( $pid, $key, $config ) {
        $type        = $config['type'];
        $label       = $config['label'] ?? '';
        $default     = $config['default'] ?? '';
        $placeholder = $config['placeholder'] ?? '';
        $options     = $config['options'] ?? [];
        $full_width  = ! empty( $config['full_width'] );

        if ( $type === 'heading' ) {
            echo '<h4 class="pp-subheading" style="grid-column: 1 / -1;">' . esc_html( $label ) . '</h4>';
            return;
        }

        if ( $type === 'custom' && is_callable( $config['callback'] ) ) {
            call_user_func( $config['callback'], $pid, $key, $config );
            return;
        }

        $val = get_post_meta( $pid, "_pp_{$key}", true );
        if ( $val === '' ) {
            $val = $default;
        }
        
        $field_class = 'pp-field';
        if ( in_array( $type, [ 'textarea', 'editor', 'radio', 'check', 'image' ] ) || $full_width ) {
            $field_class .= ' pp-field--full';
        } elseif ( in_array( $type, [ 'number', 'color' ] ) ) {
            $field_class .= ' pp-field--narrow';
        }

        if ( $type === 'image' )  $field_class .= ' pp-image-field';
        if ( $type === 'editor' ) $field_class .= ' pp-editor-field';

        echo '<div class="' . esc_attr( $field_class ) . '">';
        
        if ( $type !== 'check' ) {
            echo '<label for="pp_' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label>';
        }

        switch ( $type ) {
            case 'text':
                echo '<input type="text" id="pp_' . esc_attr( $key ) . '" name="pp_' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" placeholder="' . esc_attr( $placeholder ) . '" class="widefat">';
                break;
            case 'textarea':
                echo '<textarea id="pp_' . esc_attr( $key ) . '" name="pp_' . esc_attr( $key ) . '" rows="4" class="widefat">' . esc_textarea( $val ) . '</textarea>';
                break;
            case 'number':
                echo '<input type="number" id="pp_' . esc_attr( $key ) . '" name="pp_' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" class="small-text">';
                break;
            case 'color':
                echo '<input type="text" id="pp_' . esc_attr( $key ) . '" name="pp_' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" class="pp-color-picker" data-default="' . esc_attr( $default ) . '">';
                break;
            case 'radio':
                echo '<div class="pp-radio-group">';
                foreach ( $options as $opt_val => $opt_label ) {
                    $checked = checked( $val, $opt_val, false );
                    echo '<label class="pp-radio-label"><input type="radio" name="pp_' . esc_attr( $key ) . '" value="' . esc_attr( $opt_val ) . '" ' . $checked . '> ' . esc_html( $opt_label ) . '</label>';
                }
                echo '</div>';
                break;
            case 'check':
                $checked = checked( $val, '1', false );
                echo '<label class="pp-check-label"><input type="checkbox" name="pp_' . esc_attr( $key ) . '" value="1" ' . $checked . '> ' . esc_html( $label ) . '</label>';
                break;
            case 'select':
                echo '<select id="pp_' . esc_attr( $key ) . '" name="pp_' . esc_attr( $key ) . '" class="widefat">';
                foreach ( $options as $opt_val => $opt_label ) {
                    $selected = selected( $val, $opt_val, false );
                    echo '<option value="' . esc_attr( $opt_val ) . '" ' . $selected . '>' . esc_html( $opt_label ) . '</option>';
                }
                echo '</select>';
                break;
            case 'image':
                $img_url = $val ? wp_get_attachment_image_url( $val, 'thumbnail' ) : '';
                echo '<div class="pp-image-preview" id="pp_' . esc_attr( $key ) . '_preview">';
                if ( $img_url ) {
                    echo '<img src="' . esc_url( $img_url ) . '" style="max-height:80px; border-radius:4px;">';
                }
                echo '</div>';
                echo '<input type="hidden" id="pp_' . esc_attr( $key ) . '" name="pp_' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '">';
                echo '<button type="button" class="button pp-upload-image" data-target="pp_' . esc_attr( $key ) . '" data-preview="pp_' . esc_attr( $key ) . '_preview">' . esc_html__( 'Chọn ảnh', 'part-pages' ) . '</button> ';
                if ( $val ) {
                    echo '<button type="button" class="button pp-remove-image" data-target="pp_' . esc_attr( $key ) . '" data-preview="pp_' . esc_attr( $key ) . '_preview">' . esc_html__( 'Xoá', 'part-pages' ) . '</button>';
                }
                break;
            case 'editor':
                wp_editor( $val, 'pp_' . $key, [
                    'textarea_name' => "pp_{$key}",
                    'textarea_rows' => 6,
                    'media_buttons' => false,
                    'teeny'         => true,
                ] );
                break;
        }

        echo '</div>';
    }

    public function render_tabs_builder( $pid, $key, $config ) {
        $tabs_json = get_post_meta( $pid, '_pp_tabs_items', true );
        $tabs      = $tabs_json ? json_decode( $tabs_json, true ) : [];

        if ( empty( $tabs ) ) {
            $tabs = [
                [ 'label' => 'Tab 1', 'title' => '', 'intro' => '', 'content' => '', 'image_url' => '', 'button_text' => '', 'button_url' => '' ],
            ];
        }
        ?>
        <div class="pp-field pp-field--full" style="grid-column: 1 / -1;">
            <div class="pp-tabs-builder">
                <div class="pp-tabs-list" id="pp-tabs-list">
                    <?php foreach ( $tabs as $i => $tab ) : ?>
                        <?php $this->render_tab_item( $i, $tab ); ?>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button pp-add-tab" id="pp-add-tab">
                    <span class="dashicons dashicons-plus"></span> <?php esc_html_e( 'Thêm Tab', 'part-pages' ); ?>
                </button>
            </div>

            <input type="hidden" name="pp_tabs_items" id="pp-tabs-items" value="<?php echo esc_attr( $tabs_json ?: '' ); ?>">

            <!-- Tab item template for JS cloning -->
            <script type="text/template" id="pp-tab-template">
                <?php $this->render_tab_item( '__IDX__', [ 'label' => '', 'title' => '', 'intro' => '', 'content' => '', 'image_url' => '', 'button_text' => '', 'button_url' => '' ] ); ?>
            </script>
        </div>
        <?php
    }

    private function render_tab_item( $i, $tab ) {
        $is_template = ( $i === '__IDX__' );
        $editor_id   = 'pp_tab_content_' . ( $is_template ? 'tpl' : absint( $i ) );
        ?>
        <div class="pp-tab-item" data-index="<?php echo esc_attr( $i ); ?>">
            <div class="pp-tab-item-header">
                <span class="pp-drag-handle dashicons dashicons-menu"></span>
                <span class="pp-tab-item-label"><?php echo esc_html( $tab['label'] ?: "Tab {$i}" ); ?></span>
                <button type="button" class="pp-remove-tab button-link button-link-delete">
                    <span class="dashicons dashicons-trash"></span>
                </button>
            </div>
            <div class="pp-tab-item-body">
                <div class="pp-fields-grid">
                    <div class="pp-field">
                        <label><?php esc_html_e( 'Nhãn Tab (nav)', 'part-pages' ); ?></label>
                        <input type="text" class="pp-tab-field" data-field="label" value="<?php echo esc_attr( $tab['label'] ); ?>" placeholder="vd: Technology">
                    </div>
                    <div class="pp-field">
                        <label><?php esc_html_e( 'Tiêu đề nội dung', 'part-pages' ); ?></label>
                        <input type="text" class="pp-tab-field" data-field="title" value="<?php echo esc_attr( $tab['title'] ); ?>">
                    </div>
                    <div class="pp-field pp-field--full">
                        <label><?php esc_html_e( 'Intro ngắn', 'part-pages' ); ?></label>
                        <input type="text" class="pp-tab-field" data-field="intro" value="<?php echo esc_attr( $tab['intro'] ); ?>">
                    </div>

                    <div class="pp-field pp-field--full pp-tab-content-wrap">
                        <label><?php esc_html_e( 'Nội dung', 'part-pages' ); ?></label>

                        <?php if ( $is_template ) : ?>
                            <textarea
                                class="pp-tab-field pp-tab-content-textarea widefat"
                                data-field="content"
                                rows="8"
                                placeholder="<?php esc_attr_e( 'Nhập nội dung HTML...', 'part-pages' ); ?>"
                            ></textarea>
                        <?php else : ?>
                            <?php
                            wp_editor(
                                $tab['content'],
                                $editor_id,
                                [
                                    'textarea_name' => $editor_id,
                                    'textarea_rows' => 10,
                                    'media_buttons' => false,
                                    'teeny'         => false,
                                    'quicktags'     => true,
                                    'editor_class'  => 'pp-tab-editor',
                                ]
                            );
                            ?>
                            <input type="hidden"
                                   class="pp-tab-field pp-tab-content-hidden"
                                   data-field="content"
                                   data-editor-id="<?php echo esc_attr( $editor_id ); ?>"
                                   value="<?php echo esc_attr( $tab['content'] ); ?>">
                        <?php endif; ?>
                    </div>

                    <div class="pp-field pp-field--full pp-image-field">
                        <label><?php esc_html_e( 'Ảnh minh hoạ (URL)', 'part-pages' ); ?></label>
                        <div class="pp-image-preview">
                            <?php if ( ! empty( $tab['image_url'] ) ) : ?>
                                <img src="<?php echo esc_url( $tab['image_url'] ); ?>" style="max-height:80px;">
                            <?php endif; ?>
                        </div>
                        <input type="text" class="pp-tab-field widefat" data-field="image_url" value="<?php echo esc_attr( $tab['image_url'] ); ?>" placeholder="https://... hoặc để trống">
                        <button type="button" class="button pp-tab-upload-image"><?php esc_html_e( 'Chọn ảnh', 'part-pages' ); ?></button>
                    </div>
                    <div class="pp-field">
                        <label><?php esc_html_e( 'Button text (optional)', 'part-pages' ); ?></label>
                        <input type="text" class="pp-tab-field" data-field="button_text" value="<?php echo esc_attr( $tab['button_text'] ); ?>">
                    </div>
                    <div class="pp-field">
                        <label><?php esc_html_e( 'Button URL', 'part-pages' ); ?></label>
                        <input type="text" class="pp-tab-field" data-field="button_url" value="<?php echo esc_attr( $tab['button_url'] ); ?>">
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function save_meta_boxes( $post_id, $post ) {
        if ( ! isset( $_POST['pp_sessions_nonce'] ) ) return;
        if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pp_sessions_nonce'] ) ), 'pp_save_sessions' ) ) return;
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! current_user_can( 'edit_page', $post_id ) ) return;
        if ( $post->post_type !== 'page' ) return;

        if ( isset( $_POST['pp_session_order'] ) ) {
            $order = json_decode( sanitize_text_field( wp_unslash( $_POST['pp_session_order'] ) ), true );
            if ( is_array( $order ) ) {
                update_post_meta( $post_id, '_pp_session_order', wp_json_encode( array_map( 'sanitize_key', $order ) ) );
            }
        }

        $schema       = $this->get_schema();
        $allowed_html = wp_kses_allowed_html( 'post' );

        foreach ( $schema as $session_key => $session_config ) {
            
            $enabled_key = "pp_{$session_key}_enabled";
            update_post_meta( $post_id, "_pp_{$session_key}_enabled", isset( $_POST[ $enabled_key ] ) ? '1' : '0' );

            foreach ( $session_config['fields'] as $field_key => $field ) {
                if ( $field['type'] === 'heading' || $field['type'] === 'custom' ) {
                    continue;
                }

                $post_key = "pp_{$field_key}";
                $val      = isset( $_POST[ $post_key ] ) ? wp_unslash( $_POST[ $post_key ] ) : '';

                switch ( $field['type'] ) {
                    case 'text':
                    case 'color':
                    case 'radio':
                    case 'select':
                    case 'number':
                        update_post_meta( $post_id, "_pp_{$field_key}", sanitize_text_field( $val ) );
                        break;
                    case 'textarea':
                        update_post_meta( $post_id, "_pp_{$field_key}", sanitize_textarea_field( $val ) );
                        break;
                    case 'image':
                        update_post_meta( $post_id, "_pp_{$field_key}", absint( $val ) );
                        break;
                    case 'check':
                        update_post_meta( $post_id, "_pp_{$field_key}", isset( $_POST[ $post_key ] ) ? '1' : '0' );
                        break;
                    case 'editor':
                        update_post_meta( $post_id, "_pp_{$field_key}", wp_kses( $val, $allowed_html ) );
                        break;
                }
            }
        }

        if ( isset( $_POST['pp_tabs_items'] ) ) {
            $raw_tabs = json_decode( wp_unslash( $_POST['pp_tabs_items'] ), true );
            if ( is_array( $raw_tabs ) ) {
                $clean_tabs = [];
                foreach ( $raw_tabs as $tab ) {
                    $clean_tabs[] = [
                        'label'       => sanitize_text_field( $tab['label']       ?? '' ),
                        'title'       => sanitize_text_field( $tab['title']       ?? '' ),
                        'intro'       => sanitize_text_field( $tab['intro']       ?? '' ),
                        'content'     => wp_kses( $tab['content'] ?? '', $allowed_html ),
                        'image_url'   => esc_url_raw( $tab['image_url']   ?? '' ),
                        'button_text' => sanitize_text_field( $tab['button_text'] ?? '' ),
                        'button_url'  => esc_url_raw( $tab['button_url']  ?? '' ),
                    ];
                }
                update_post_meta( $post_id, '_pp_tabs_items', wp_json_encode( $clean_tabs ) );
            }
        }
    }
}
