jQuery(document).ready(function () {

    const calendarLoader = jQuery('.sidebar__calendar .mscec-loader');
    const filterLoader = jQuery('.sidebar__filter .mscec-loader');

    jQuery('.mscec-datepicker').datepicker({
        toggleSelected: 'click',
        todayButton: new Date(),
        showOtherMonths: true,
        onRenderCell: function (date, cellType) {
            let currentDate = date.getDate();

            let currentDay = `${currentDate}`.padStart(2, "0");
            let currentMonth = date.getMonth() + 1;
            let currentYear = date.getFullYear();
            let fullDate = `${currentYear}-${currentMonth}-${currentDay}`;

            let eventDates = ajax.dates;

            if (cellType == 'day' && eventDates.indexOf(fullDate) != -1) {
                return {
                    html: currentDate + '<span class="dp-note"></span>'
                }
            } else {
                return {
                    disabled: false
                }
            }
        },
        onSelect: (formattedDate, date, inst) => {

            calendarLoader.addClass('show');

            jQuery.ajax({
                url: ajax.url,
                type: 'GET',
                data: {
                    action: 'events_calendar',
                    nonce: jQuery('.events_calendar_nonce').val(),
                    date: formattedDate
                },
                success: function (data) {
                    calendarLoader.removeClass('show');
                    jQuery('.mscec-events').html(data);
                },
                error: function (error) {
                    calendarLoader.removeClass('show');
                    console.error(error);
                }
            });
        }
    })

    jQuery('.mscec-datepicker').data('datepicker').selectDate(new Date());

    const eventsFilterForm = document.getElementById('mscec-filter');

    jQuery('#mscec-filter__date').datepicker({
        todayButton: new Date()
    });

    const eventsFilterSelect = document.getElementById('mscec-filter__organizer');

    if (eventsFilterSelect) {
        const choices = new Choices(
            eventsFilterSelect,
            {
                loadingText: 'Загрузка...',
                noResultsText: 'Ничего не найдено',
                noChoicesText: 'Не из чего выбирать',
                itemSelectText: 'Нажмите чтобы выбрать',
                shouldSort: false
            }
        );
    }

    jQuery('#mscec-filter').submit(function (e) {

        e.preventDefault();

        let data = jQuery('#mscec-filter').serialize();

        console.log(typeof data);

        filterLoader.addClass('show');

        jQuery.ajax({
            url: ajax.url,
            type: 'GET',
            data: data,
            success: function (data) {

                filterLoader.removeClass('show');

                jQuery('.mscec-events').html(data);
            },
            error: function (error) {
                filterLoader.removeClass('show');
                console.error(error);
            }
        });
    });
});