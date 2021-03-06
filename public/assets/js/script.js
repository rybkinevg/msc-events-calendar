"use strict";

jQuery(document).ready(function ($) {

    const calendarLoader = $('.sidebar__calendar .mscec-loader');
    const filterLoader = $('.sidebar__filter .mscec-loader');

    let loadMoreBtn;

    $('.mscec-query .mscec-sidebar-spoiler').click(function () {
        $(this).parent().next().slideToggle();
        $(this).toggleClass('active');
    });

    if ('undefined' !== typeof events_query) {
        loadMoreBtn = $('.mscec-loadmore-btn');
        loadMoreBtn.click(load_more);
    }

    $('.mscec-datepicker').datepicker({
        toggleSelected: 'click',
        todayButton: new Date(),
        showOtherMonths: true,
        onRenderCell: function (date, cellType) {
            let currentDate = date.getDate();

            let currentDay = `${currentDate}`.padStart(2, "0");
            let currentMonth = `${date.getMonth() + 1}`.padStart(2, "0");
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

            calendarLoader.addClass('loading');

            $.ajax({
                url: ajax.url,
                type: 'GET',
                data: {
                    action: 'events_calendar',
                    nonce: $('.events_calendar_nonce').val(),
                    events_date: formattedDate
                },
                success: function (data) {

                    calendarLoader.removeClass('loading');

                    $('.mscec-events').html(data);

                    $('.mscec-query').empty();
                    $('.mscec-query').append($('.mscec-query__info'));

                    $('.mscec-query .mscec-sidebar-spoiler').click(function () {
                        $(this).parent().next().slideToggle();
                        $(this).toggleClass('active');
                    });

                    loadMoreBtn = $('.mscec-loadmore-btn');

                    if ('undefined' !== typeof events_query) {
                        loadMoreBtn.click(load_more);
                    }
                },
                error: function (error) {

                    calendarLoader.removeClass('loading');
                    console.error(error);
                }
            });
        }
    })

    const eventsFilterForm = document.getElementById('mscec-filter');

    jQuery('#mscec-filter__date').datepicker({
        todayButton: new Date()
    });

    // Выпадающий список организаторов
    const eventsFilterSelectOrganizer = document.getElementById('mscec-filter__organizer');

    if (eventsFilterSelectOrganizer) {
        const eventsOrganizerFilter = new Choices(
            eventsFilterSelectOrganizer,
            {
                loadingText: 'Загрузка...',
                noResultsText: 'Ничего не найдено',
                noChoicesText: 'Не из чего выбирать',
                itemSelectText: 'Нажмите чтобы выбрать',
                shouldSort: false
            }
        );
    }

    // Выпадающий список категорий
    const eventsFilterSelectCat = document.getElementById('mscec-filter__cat');

    if (eventsFilterSelectCat) {
        const eventsCatFilter = new Choices(
            eventsFilterSelectCat,
            {
                loadingText: 'Загрузка...',
                noResultsText: 'Ничего не найдено',
                noChoicesText: 'Не из чего выбирать',
                itemSelectText: 'Нажмите чтобы выбрать',
                shouldSort: false
            }
        );
    }

    // Выпадающий список форм проведения
    const eventsFilterSelectForm = document.getElementById('mscec-filter__form');

    if (eventsFilterSelectForm) {
        const eventsFormFilter = new Choices(
            eventsFilterSelectForm,
            {
                loadingText: 'Загрузка...',
                noResultsText: 'Ничего не найдено',
                noChoicesText: 'Не из чего выбирать',
                itemSelectText: 'Нажмите чтобы выбрать',
                shouldSort: false
            }
        );
    }

    $('#mscec-filter').submit(function (e) {

        e.preventDefault();

        let data = $('#mscec-filter').serialize();

        filterLoader.addClass('loading');

        $.ajax({
            url: ajax.url,
            type: 'GET',
            data: data,
            success: function (data) {

                filterLoader.removeClass('loading');

                $('.mscec-events').html(data);

                $('.mscec-query').empty();
                $('.mscec-query').append($('.mscec-query__info'));

                $('.mscec-query .mscec-sidebar-spoiler').click(function () {
                    $(this).parent().next().slideToggle();
                    $(this).toggleClass('active');
                });

                if ('undefined' !== typeof events_query) {
                    loadMoreBtn = $('.mscec-loadmore-btn');
                    loadMoreBtn.click(load_more);
                }
            },
            error: function (error) {

                filterLoader.removeClass('loading');
                console.error(error);
            }
        });
    });

    function load_more() {

        $(this).text('Загрузка...');

        var data = {
            'action': 'loadmore',
            'query': events_query,
            'page': current_page
        };

        $.ajax({
            url: ajax.url, // обработчик
            data: data, // данные
            type: 'POST', // тип запроса
            success: function (data) {

                current_page++;

                loadMoreBtn.text('Загрузить ещё').before(data);

                if (current_page == max_pages) $(".mscec-loadmore-btn").remove();
            }
        });
    }

    $('.sidebar__item .mscec-sidebar-spoiler').click(function () {
        console.log($(this));
        $(this).parent().next().slideToggle();
        $(this).toggleClass('active');
    });

});