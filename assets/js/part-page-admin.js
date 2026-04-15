/* global pp_admin */
(function ($) {
    'use strict';

    /* ================================================================
       1. SORTABLE SESSION LIST
       ================================================================ */
    var $sortable = $('#pp-sessions-sortable');

    if ($sortable.length) {
        $sortable.sortable({
            handle: '.pp-drag-handle',
            axis: 'y',
            placeholder: 'pp-session-row ui-sortable-placeholder',
            tolerance: 'pointer',
            update: function () {
                updateSessionOrder();
            }
        });
    }

    function updateSessionOrder() {
        var order = [];
        $sortable.find('.pp-session-row').each(function () {
            order.push($(this).data('key'));
        });
        $('#pp-session-order').val(JSON.stringify(order));
    }

    /* ================================================================
       2. SESSION ACCORDION
       ================================================================ */
    $(document).on('click', '.pp-row-header', function (e) {
        // Don't toggle if clicking the checkbox or drag handle
        if ($(e.target).closest('.pp-toggle, .pp-drag-handle').length) return;

        var $row = $(this).closest('.pp-session-row');
        var $body = $row.find('.pp-row-body');

        if ($row.hasClass('is-open')) {
            $body.slideUp(200);
            $row.removeClass('is-open');
        } else {
            // Close others
            $sortable.find('.pp-session-row.is-open').each(function () {
                $(this).removeClass('is-open').find('.pp-row-body').slideUp(200);
            });
            $body.slideDown(200);
            $row.addClass('is-open');
        }
    });

    /* ================================================================
       3. SESSION ENABLE TOGGLE
       ================================================================ */
    $(document).on('change', '.pp-session-row .pp-toggle input[type="checkbox"]', function () {
        var $row = $(this).closest('.pp-session-row');
        if ($(this).is(':checked')) {
            $row.addClass('is-enabled');
        } else {
            $row.removeClass('is-enabled');
        }
    });

    /* ================================================================
       4. COLOR PICKER
       ================================================================ */
    $('.pp-color-picker').wpColorPicker({
        defaultColor: function () {
            return $(this).data('default') || '#ffffff';
        }
    });

    /* ================================================================
       5. IMAGE UPLOADER
       ================================================================ */
    var mediaFrame;

    $(document).on('click', '.pp-upload-image', function (e) {
        e.preventDefault();
        var $btn     = $(this);
        var targetId = $btn.data('target');
        var previewId = $btn.data('preview');

        mediaFrame = wp.media({
            title:    pp_admin.upload_title || 'Chọn ảnh',
            button:   { text: pp_admin.upload_btn || 'Dùng ảnh này' },
            multiple: false
        });

        mediaFrame.on('select', function () {
            var attachment = mediaFrame.state().get('selection').first().toJSON();
            $('#' + targetId).val(attachment.id);
            var $preview = $('#' + previewId);
            $preview.empty();
            $preview.append('<img src="' + (attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url) + '" style="max-height:80px; border-radius:4px;">');
            // Show remove button
            $btn.next('.pp-remove-image').remove();
            $btn.after('<button type="button" class="button pp-remove-image" data-target="' + targetId + '" data-preview="' + previewId + '">Xoá</button>');
        });

        mediaFrame.open();
    });

    $(document).on('click', '.pp-remove-image', function (e) {
        e.preventDefault();
        var targetId = $(this).data('target');
        var previewId = $(this).data('preview');
        $('#' + targetId).val('');
        $('#' + previewId).empty();
        $(this).remove();
    });

    /* ================================================================
       6. TABS BUILDER
       ================================================================ */
    var $tabsList    = $('#pp-tabs-list');
    var $tabsInput   = $('#pp-tabs-items');
    var $tabTemplate = $('#pp-tab-template');

    // Make tab items sortable
    if ($tabsList.length) {
        $tabsList.sortable({
            handle: '.pp-drag-handle',
            placeholder: 'pp-tab-item ui-sortable-placeholder',
            update: serializeTabs
        });
    }

    // Add tab — insert from JS template, then init TinyMCE on the new textarea
    $('#pp-add-tab').on('click', function () {
        var idx = $tabsList.find('.pp-tab-item').length;
        var html = $tabTemplate.html().replace(/__IDX__/g, idx);
        var $new = $(html);
        $tabsList.append($new);
        $new.addClass('is-open');

        // Initialize TinyMCE on the newly added textarea
        var $textarea = $new.find('.pp-tab-content-textarea');
        if ($textarea.length && typeof tinymce !== 'undefined') {
            var newEditorId = 'pp_tab_content_new_' + idx + '_' + Date.now();
            $textarea.attr('id', newEditorId);
            // Remove the pp-tab-field class from textarea; use hidden input instead
            $textarea.removeClass('pp-tab-field');
            $textarea.removeAttr('data-field');

            // Insert hidden input BEFORE textarea for JS serialization
            $textarea.before(
                $('<input>', {
                    type: 'hidden',
                    'class': 'pp-tab-field pp-tab-content-hidden',
                    'data-field': 'content',
                    'data-editor-id': newEditorId,
                    value: ''
                })
            );

            // Init TinyMCE with basic settings matching other editors
            tinymce.init({
                selector: '#' + newEditorId,
                menubar: false,
                statusbar: false,
                resize: true,
                toolbar: 'bold italic underline | bullist numlist | link | removeformat | undo redo | code',
                plugins: 'lists link code',
                height: 280,
                setup: function (editor) {
                    // Sync to hidden input on every change
                    editor.on('change keyup input', function () {
                        var hiddenInput = document.querySelector(
                            'input.pp-tab-content-hidden[data-editor-id="' + editor.id + '"]'
                        );
                        if (hiddenInput) {
                            hiddenInput.value = editor.getContent();
                        }
                        serializeTabs();
                    });
                }
            });
        }

        serializeTabs();
    });

    // Toggle tab item body
    $(document).on('click', '.pp-tab-item-header', function (e) {
        if ($(e.target).closest('.pp-remove-tab').length) return;
        var $item = $(this).closest('.pp-tab-item');
        $item.toggleClass('is-open');
    });

    // Remove tab
    $(document).on('click', '.pp-remove-tab', function (e) {
        e.stopPropagation();
        var $item = $(this).closest('.pp-tab-item');
        if ($tabsList.find('.pp-tab-item').length > 1) {
            $item.remove();
            serializeTabs();
        } else {
            alert('Phải có ít nhất 1 tab.');
        }
    });

    // Live update label in header
    $(document).on('input', '.pp-tab-field[data-field="label"]', function () {
        var $item = $(this).closest('.pp-tab-item');
        var val = $(this).val() || 'Tab';
        $item.find('.pp-tab-item-label').text(val);
        serializeTabs();
    });

    // Any field change triggers serialize
    $(document).on('change input', '.pp-tab-field', function () {
        serializeTabs();
    });

    // Tab image upload
    $(document).on('click', '.pp-tab-upload-image', function (e) {
        e.preventDefault();
        var $btn  = $(this);
        var $item = $btn.closest('.pp-tab-item');
        var frame = wp.media({
            title:    pp_admin.upload_title || 'Chọn ảnh',
            button:   { text: pp_admin.upload_btn || 'Dùng ảnh này' },
            multiple: false
        });
        frame.on('select', function () {
            var att  = frame.state().get('selection').first().toJSON();
            var url  = att.url;
            $item.find('.pp-tab-field[data-field="image_url"]').val(url).trigger('change');
            var $preview = $item.find('.pp-image-preview');
            $preview.empty().append('<img src="' + url + '" style="max-height:80px;">');
        });
        frame.open();
    });

    /**
     * serializeTabs — reads all tab fields and encodes to JSON.
     * For content fields:
     *   - Saved tabs: reads from TinyMCE via tinymce.get(editorId).getContent()
     *   - New tabs:   reads from the hidden input (kept in sync by TinyMCE setup callback)
     * For all other fields: reads .val() directly.
     */
    function serializeTabs() {
        var tabs = [];
        $tabsList.find('.pp-tab-item').each(function () {
            var $item = $(this);
            var tab   = {};

            $item.find('.pp-tab-field').each(function () {
                var $field = $(this);
                var field  = $field.data('field');

                if ( field === 'content' ) {
                    // Try to read from TinyMCE first
                    var editorId = $field.data('editor-id');
                    if ( editorId && typeof tinymce !== 'undefined' && tinymce.get(editorId) ) {
                        tab[field] = tinymce.get(editorId).getContent();
                    } else {
                        // Fallback: textarea or hidden input value
                        tab[field] = $field.val() || '';
                    }
                } else {
                    tab[field] = $field.val();
                }
            });

            tabs.push(tab);
        });
        $tabsInput.val(JSON.stringify(tabs));
    }

    /**
     * syncEditorsAndSerialize — called just before WordPress form submit.
     * Forces TinyMCE to flush its content to the underlying textareas/hidden inputs,
     * then re-runs serializeTabs to ensure pp_tabs_items is up to date.
     */
    function syncEditorsAndSerialize() {
        if ( typeof tinymce !== 'undefined' ) {
            // Sync each TinyMCE editor that belongs to a tab
            $tabsList.find('input.pp-tab-content-hidden[data-editor-id]').each(function () {
                var editorId = $(this).data('editor-id');
                var editor   = tinymce.get(editorId);
                if ( editor ) {
                    $(this).val( editor.getContent() );
                }
            });
        }
        serializeTabs();
    }

    // Sync before WordPress saves the page (fires on both Publish and Update)
    $('#post').on('submit', function () {
        syncEditorsAndSerialize();
    });

    /* ================================================================
       7. DETECT TEMPLATE CHANGE → REFRESH META BOX
       ================================================================ */
    var $templateSelect = $('#page_template');

    if ($templateSelect.length) {
        function toggleMetaBox() {
            var tpl = $templateSelect.val();
            var isPartPage = (tpl === pp_admin.template_key);
            var $mb = $('#pp_sessions');

            if ($mb.length) {
                if (isPartPage) {
                    $mb.show();
                } else {
                    $mb.find('.pp-sessions-wrap').hide();
                    $mb.find('.pp-inactive-notice').show();
                }
            }
        }

        $templateSelect.on('change', toggleMetaBox);
        // Run on load
        toggleMetaBox();
    }

}(jQuery));
