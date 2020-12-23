jQuery(document).ready(function () {

    const importPanel = document.getElementById('one-panel');
    const notice = document.getElementById('message');
    const noticeText = document.getElementsByClassName('message-text');
    const noticePoint = jQuery(notice).scrollTop();
    const importForm = document.getElementById('events-import__form');
    const inputFile = document.getElementById('events-import__file');
    const importButtonLoader = document.getElementsByClassName('import-submit-loader');
    const importButton = document.getElementById('events-import__submit');
    let insertForm = '';
    let insertButton = '';
    let insertButtonLoader = '';

    jQuery(inputFile).change(function () {
        if (jQuery(this).val()) {
            jQuery(importButton).removeAttr('disabled');
        }
        else {
            jQuery(importButton).attr('disabled', true);
        }
    });

    jQuery(importForm).on('submit', function (event) {

        event.preventDefault();

        jQuery(notice).removeClass('updated error');
        jQuery(notice).show('fast');
        jQuery("html, body").animate({ scrollTop: noticePoint }, "slow");

        jQuery(importButton).attr('disabled', true);
        jQuery(importButtonLoader).removeClass('disabled');
        jQuery(noticeText).text('Происходит загрузка файла на сервер');

        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            contentType: false,
            processData: false,
            data: new FormData(importForm),
            success: function (respond, status, jqXHR) {
                if (respond.success) {

                    jQuery(inputFile).attr('disabled', true);

                    jQuery(importButtonLoader).addClass('disabled');

                    jQuery(notice).addClass('updated');
                    jQuery("html, body").animate({ scrollTop: noticePoint }, "slow");
                    jQuery(noticeText).text('Загрузка файла завершена');

                    /**
                     * @todo append заменить на text например, чтобы при повторном нажатии на сабмит не добавлялось ещё разметки
                     */
                    jQuery(importPanel).append(respond.data);

                    jQuery('.double-scroll').doubleScroll({
                        contentCss: {
                            'overflow-x': 'auto',
                            'overflow-y': 'auto'
                        },
                    });

                    insertForm = document.getElementById('events-insert__form');
                    insertButton = document.getElementById('events-insert__submit');
                    insertButtonLoader = document.getElementsByClassName('insert-submit-loader');

                    insertFormHandler()
                }
                else {
                    jQuery(importButtonLoader).addClass('disabled');
                    jQuery(notice).addClass('error');
                    jQuery("html, body").animate({ scrollTop: noticePoint }, "slow");
                    jQuery(noticeText).text('Ошибка: ' + respond.data);
                }
            },
            error: function (jqXHR, status, errorThrown) {
                jQuery(notice).addClass('error');
                jQuery("html, body").animate({ scrollTop: noticePoint }, "slow");
                jQuery(noticeText).text('Ошибка AJAX запроса: ' + status + ', ' + jqXHR + ', ' + errorThrown);
            }
        });
    });

    function insertFormHandler() {
        jQuery(insertForm).on('submit', function (event) {

            event.preventDefault();

            jQuery(insertButton).attr('disabled', true);

            jQuery(insertButtonLoader).removeClass('disabled');

            jQuery(notice).removeClass('updated error');
            jQuery("html, body").animate({ scrollTop: noticePoint }, "slow");
            jQuery(noticeText).text('Происходит импорт в базу данных');

            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                contentType: false,
                processData: false,
                data: new FormData(insertForm),
                success: function (respond, status, jqXHR) {
                    if (respond.success) {
                        jQuery(insertButtonLoader).addClass('disabled');
                        jQuery(notice).addClass('updated');
                        jQuery("html, body").animate({ scrollTop: noticePoint }, "slow");
                        jQuery(noticeText).text('Импорт данных успешно завершен: ' + respond.data);
                    }
                    else {
                        jQuery(insertButton).removeAttr('disabled');
                        jQuery(insertButtonLoader).addClass('disabled');
                        jQuery(notice).addClass('error');
                        jQuery("html, body").animate({ scrollTop: noticePoint }, "slow");
                        jQuery(noticeText).text('Ошибка: ' + respond.data);
                    }
                },
                error: function (jqXHR, status, errorThrown) {
                    jQuery(insertButton).removeAttr('disabled');
                    jQuery(insertButtonLoader).addClass('disabled');
                    jQuery(notice).addClass('error');
                    jQuery("html, body").animate({ scrollTop: noticePoint }, "slow");
                    jQuery(noticeText).text('Ошибка AJAX запроса: ' + status + ', ' + jqXHR + ', ' + errorThrown);
                }
            });
        });
    }

    if (jQuery('body').hasClass('post-type-events_organizers')) {
        jQuery('#menu-posts-events, #menu-posts-events a.wp-has-submenu')
            .addClass('wp-menu-open wp-has-current-submenu wp-has-submenu')
            .removeClass('wp-not-current-submenu')
            .find("li a[href='edit.php?post_type=events_organizers']")
            .parent()
            .addClass('current');
    }

});