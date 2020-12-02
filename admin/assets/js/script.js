jQuery(document).ready(function () {

    const importPage = document.getElementById('events-import-page');
    const importForm = document.getElementById('events-import__form');

    jQuery(importForm).on('submit', function (event) {
        event.preventDefault();
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            contentType: false,
            processData: false,
            data: new FormData(importForm),
            success: function (respond, status, jqXHR) {
                if (respond.success) {
                    console.log(respond.data.response);

                    /**
                     * @todo append заменить на text например, чтобы при повторном нажатии на сабмит не добавлялось ещё разметки
                     */
                    jQuery(importPage).append(respond.data.html);
                }
                else {
                    console.log(respond.data);
                }
            },
            error: function (jqXHR, status, errorThrown) {
                console.log('Ошибка AJAX запроса: ' + status + ', ' + jqXHR + ', ' + errorThrown);
            }
        });
    });

});