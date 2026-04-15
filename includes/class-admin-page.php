<?php
defined('ABSPATH') || exit;

/**
 * Registers the "Part Pages" admin menu with a quick-start guide
 * and a one-click CF7 sample form creator.
 */
class PP_Admin_Page
{

    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_post_pp_create_sample_form', [$this, 'handle_create_form']);
    }

    public function add_menu()
    {
        add_menu_page(
            __('Part Pages', 'part-pages'),
            __('Part Pages', 'part-pages'),
            'edit_pages',
            'part-pages',
            [$this, 'render_page'],
            'dashicons-layout',
            59
        );
    }

    /* ================================================================
       RENDER
       ================================================================ */

    public function render_page()
    {
        // Success / error notice after form creation
        $created_id = isset($_GET['pp_form_created']) ? absint($_GET['pp_form_created']) : 0;
        $error = isset($_GET['pp_form_error']) ? sanitize_text_field(wp_unslash($_GET['pp_form_error'])) : '';
        ?>
        <div class="wrap pp-admin-wrap">
            <h1><?php esc_html_e('Part Pages', 'part-pages'); ?></h1>
            <p class="pp-admin-version">v<?php echo esc_html(PP_VERSION); ?></p>

            <?php if ($created_id): ?>
                <div class="notice notice-success is-dismissible">
                    <p>
                        ✅ <?php esc_html_e('Form mẫu đã được tạo thành công!', 'part-pages'); ?>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wpcf7&action=edit&post=' . $created_id)); ?>"
                            target="_blank">
                            <?php esc_html_e('Xem / chỉnh sửa form →', 'part-pages'); ?>
                        </a>
                    </p>
                </div>
            <?php elseif ($error): ?>
                <div class="notice notice-error is-dismissible">
                    <p>❌ <?php echo esc_html($error); ?></p>
                </div>
            <?php endif; ?>

            <div class="pp-admin-grid">

                <!-- Quick Start -->
                <div class="pp-admin-card">
                    <h2>🚀 Bắt đầu nhanh</h2>
                    <ol>
                        <li>Tạo hoặc chỉnh sửa một <strong>Page</strong> bất kỳ.</li>
                        <li>Trong <em>Page Attributes</em>, chọn Template: <strong>"Part Page – Event / Launch"</strong>.</li>
                        <li>Meta box <strong>"Part Page Sessions"</strong> sẽ xuất hiện.</li>
                        <li>Kéo ⠿ để sắp xếp thứ tự — bật/tắt từng session — nhập nội dung.</li>
                        <li>Publish &amp; xem kết quả!</li>
                    </ol>
                </div>

                <!-- Sessions overview -->
                <div class="pp-admin-card">
                    <h2>📦 Các Sessions</h2>
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th>Session</th>
                                <th>Mô tả</th>
                                <th>Yêu cầu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>🖼 Hero</td>
                                <td>Logo + Subtitle + Tiêu đề 3 dòng</td>
                                <td>—</td>
                            </tr>
                            <tr>
                                <td>🎬 Video</td>
                                <td>Full-width 16:9 (YouTube/Vimeo/MP4)</td>
                                <td>—</td>
                            </tr>
                            <tr>
                                <td>📅 Event Card</td>
                                <td>2-cột: Ảnh + Thông tin sự kiện + CTA</td>
                                <td>—</td>
                            </tr>
                            <tr>
                                <td>🗂 Tabs</td>
                                <td>7 tabs dạng bảng nội dung</td>
                                <td>—</td>
                            </tr>
                            <tr>
                                <td>📝 Text Section</td>
                                <td>Tiêu đề lớn + Nhiều đoạn văn</td>
                                <td>—</td>
                            </tr>
                            <tr>
                                <td>📢 CTA Banner</td>
                                <td>Ảnh nền + Text nổi + Button</td>
                                <td>—</td>
                            </tr>
                            <tr>
                                <td>📋 Form CF7</td>
                                <td>Nhúng Contact Form 7</td>
                                <td>Contact Form 7</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- CF7 Status + Create Form -->
                <div class="pp-admin-card">
                    <h2>🔌 Contact Form 7</h2>
                    <?php if (class_exists('WPCF7')): ?>
                        <p style="color:#00a32a;">✅ Đã cài đặt và kích hoạt.</p>

                        <!-- Create sample form button -->
                        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
                            style="margin-bottom:16px;">
                            <input type="hidden" name="action" value="pp_create_sample_form">
                            <?php wp_nonce_field('pp_create_sample_form', 'pp_cf7_nonce'); ?>
                            <button type="submit" class="button button-primary" style="font-size:14px;padding:6px 16px;">
                                ✨ <?php esc_html_e('Tạo Form Mẫu (Device Contact Form)', 'part-pages'); ?>
                            </button>
                            <p class="description" style="margin-top:6px;">
                                <?php esc_html_e('Tự động tạo CF7 form giống hệt ảnh mẫu: First name, Last name, Phone, Email, Company, State, Website URL, Checkbox thiết bị, Radio ngày & nguồn.', 'part-pages'); ?>
                            </p>
                        </form>

                        <?php
                        $forms = get_posts([
                            'post_type' => 'wpcf7_contact_form',
                            'posts_per_page' => -1,
                            'post_status' => 'publish',
                        ]);
                        if ($forms):
                            ?>
                            <p><strong><?php esc_html_e('Các form hiện có:', 'part-pages'); ?></strong></p>
                            <ul>
                                <?php foreach ($forms as $form): ?>
                                    <li>
                                        <strong>[<?php echo esc_html($form->ID); ?>]</strong>
                                        <?php echo esc_html($form->post_title); ?>
                                        <a href="<?php echo esc_url(admin_url('admin.php?page=wpcf7&action=edit&post=' . $form->ID)); ?>"
                                            style="margin-left:8px;font-size:12px;">Chỉnh sửa</a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                    <?php else: ?>
                        <p style="color:#d63638;">❌ Chưa cài đặt.</p>
                        <a href="<?php echo esc_url(admin_url('plugin-install.php?s=contact+form+7&tab=search&type=term')); ?>"
                            class="button button-primary">
                            Cài Contact Form 7
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Tips -->
                <div class="pp-admin-card">
                    <h2>💡 Mẹo sử dụng</h2>
                    <ul>
                        <li>Button <strong>"Enquire Now"</strong> / <strong>"Register Now"</strong> có thể scroll đến Form CF7
                            hoặc link ngoài.</li>
                        <li>Session <strong>Tabs</strong> hỗ trợ tối đa 7 tabs — có thể thêm/xóa linh hoạt.</li>
                        <li>Session <strong>Event Card</strong> trên mobile sẽ hiển thị ảnh phía trên, text phía dưới.</li>
                        <li>Tất cả ảnh lưu dạng <strong>Attachment ID</strong> — có thể thay ảnh mà không mất link.</li>
                        <li>Sau khi tạo Form Mẫu, copy <strong>Form ID</strong> vào session <strong>"Contact Form 7"</strong>
                            của Page.</li>
                    </ul>
                </div>

            </div><!-- .pp-admin-grid -->
        </div><!-- .wrap -->
        <?php
    }

    /* ================================================================
       HANDLE FORM CREATION
       ================================================================ */

    public function handle_create_form()
    {
        // Security
        if (!current_user_can('edit_pages')) {
            wp_die('Không có quyền.');
        }
        check_admin_referer('pp_create_sample_form', 'pp_cf7_nonce');

        if (!class_exists('WPCF7_ContactForm')) {
            wp_redirect(add_query_arg('pp_form_error', urlencode('Contact Form 7 chưa được kích hoạt.'), admin_url('admin.php?page=part-pages')));
            exit;
        }

        $form_id = $this->create_device_contact_form();

        if ($form_id) {
            wp_redirect(add_query_arg('pp_form_created', $form_id, admin_url('admin.php?page=part-pages')));
        } else {
            wp_redirect(add_query_arg('pp_form_error', urlencode('Không thể tạo form. Vui lòng thử lại.'), admin_url('admin.php?page=part-pages')));
        }
        exit;
    }

    /* ================================================================
       CF7 FORM TEMPLATE — giống hệt ảnh mẫu
       ================================================================ */

    private function create_device_contact_form()
    {
        // ── Form body ─────────────────────────────────────────────
        $form_body = '
<div class="pp-cf7-grid">
    <div class="pp-cf7-col-half">
        <label class="pp-cf7-label">First name <abbr class="pp-required" title="required">*</abbr>
            [text* first-name placeholder "First name"]
        </label>
    </div>
    <div class="pp-cf7-col-half">
        <label class="pp-cf7-label">Last name <abbr class="pp-required" title="required">*</abbr>
            [text* last-name placeholder "Last name"]
        </label>
    </div>
</div>

<label class="pp-cf7-label">Phone number <abbr class="pp-required" title="required">*</abbr>
    [tel* phone-number placeholder "Phone number"]
</label>

<label class="pp-cf7-label">Email <abbr class="pp-required" title="required">*</abbr>
    [email* your-email placeholder "Email"]
</label>

<div class="pp-cf7-grid">
    <div class="pp-cf7-col-half">
        <label class="pp-cf7-label">Company name <abbr class="pp-required" title="required">*</abbr>
            [text* company-name placeholder "Company name"]
        </label>
    </div>
    <div class="pp-cf7-col-half">
        <label class="pp-cf7-label">State/Region <abbr class="pp-required" title="required">*</abbr>
            [text* state-region placeholder "State/Region"]
        </label>
    </div>
</div>

<label class="pp-cf7-label">Website URL
    [url website-url placeholder "Website URL"]
</label>

<div class="pp-cf7-radio-group">
    <p class="pp-cf7-group-label pp-cf7-label-highlight">How did you find us?</p>
    [radio how-find-us use_label_element class:pp-cf7-radio-list
        "Google"
        "Instagram"
        "Facebook"
        "Facebook Ad"
        "Through a Friend"
        "Returning Student"
    ]
</div>

[submit class:pp-cf7-submit "Send Enquiry"]';

        // ── Email body ────────────────────────────────────────────
        $mail_body = 'From: [first-name] [last-name] <[your-email]>
Subject: New Device Enquiry

First name:   [first-name]
Last name:    [last-name]
Phone:        [phone-number]
Email:        [your-email]
Company:      [company-name]
State/Region: [state-region]
Website:      [website-url]

Devices interested in:
[devices]

Preferred date: [preferred-date]
How did you find us: [how-find-us]

---
Sent via Part Pages plugin';

        // ── Create via CF7 API ────────────────────────────────────
        $contact_form = WPCF7_ContactForm::get_template([
            'title' => 'Part Pages – Device Contact Form',
            'locale' => get_locale(),
        ]);

        if (!$contact_form) {
            return false;
        }

        $contact_form->set_properties([
            'form' => $form_body,
            'mail' => [
                'active' => true,
                'recipient' => get_option('admin_email'),
                'sender' => sprintf('%s <%s>', get_bloginfo('name'), get_option('admin_email')),
                'subject' => 'New Enquiry: [first-name] [last-name]',
                'body' => $mail_body,
                'additional_headers' => 'Reply-To: [your-email]',
                'attachments' => '',
                'use_html' => false,
                'exclude_blank' => true,
            ],
        ]);

        $result = $contact_form->save();

        return $result ? $contact_form->id() : false;
    }
}
