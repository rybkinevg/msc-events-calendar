jQuery(document).ready(function () {

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
            jQuery.ajax({
                url: ajax.url,
                type: 'GET',
                data: {
                    action: 'events_filter',
                    nonce: jQuery('.events_filter_nonce').val(),
                    date: formattedDate
                },
                success: function (data) {
                    jQuery('.mscec-list').html(data);
                },
                error: function (error) {
                    console.error(error);
                }
            });

            jQuery('.mscec-datepicker').blur();
        }
    })

    const eventsFilterForm = document.getElementById('mscec-filter');

    jQuery('#mscec-filter').submit(function (e) {

        e.preventDefault();

        let data = jQuery('#mscec-filter').serialize();

        jQuery('.wpsec-loader').addClass('show');

        jQuery.ajax({
            url: ajax.url,
            type: 'GET',
            data: data,
            success: function (data) {
                jQuery('.wpsec-loader').removeClass('show');
                console.log(data);
                if (data) {
                    jQuery('.mscec-list').html(data);
                }
            },
            error: function (error) {
                console.error(error);
            }
        });
    });
});