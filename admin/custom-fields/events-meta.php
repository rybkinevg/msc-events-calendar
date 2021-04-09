<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

// Организаторы
$organizers = [
    '' => '-- выберите значение из списка --'
];

$posts = get_posts(
    [
        'numberposts' => -1,
        'post_type'   => 'events_organizers',
        'post_status' => 'publish'
    ]
);

foreach ($posts as $post) {
    $organizers[$post->post_name] = $post->post_title;
}

wp_reset_postdata();

// Категории мероприятия
$event_cat = MSCEC_Public::get_event_cats();

array_unshift($event_cat, '-- выберите значение из списка --');

// Формы проведения мероприятия
$event_form = MSCEC_Public::get_event_forms();

array_unshift($event_form, '-- выберите значение из списка --');

Container::make('post_meta', 'Информация о мероприятии')
    ->where('post_type', '=', 'events')
    ->add_fields(
        [
            Field::make('date', 'date', 'Дата мероприятия')
                ->set_width(100),
            Field::make('time', 'time_start', 'Время начала')
                ->set_width(50)
                ->set_storage_format('H:i')
                ->set_picker_options(
                    [
                        'time_24hr' => true,
                        'altInput' => true,
                        'altFormat' => 'H:i',
                        'dateFormat' => 'H:i',
                        'enableSeconds' => false,
                    ]
                ),
            Field::make('time', 'time_end', 'Время завершения')
                ->set_width(50)
                ->set_storage_format('H:i')
                ->set_picker_options(
                    [
                        'time_24hr' => true,
                        'altInput' => true,
                        'altFormat' => 'H:i',
                        'dateFormat' => 'H:i',
                        'enableSeconds' => false,
                    ]
                ),
            Field::make('select', 'organizer', 'Организатор мероприятия')
                ->add_options($organizers),
            Field::make('html', 'events_null_organizer')
                ->set_conditional_logic(
                    [
                        [
                            'field' => 'organizer',
                            'value' => 'null',
                            'compare' => '=',
                        ]
                    ]
                )
                ->set_html('<strong>Ошибка при попытке получить список организаторов</strong><p>К сожалению, организаторов не найдено, чтобы иметь возможность выбирать организаторов нужно их сначала создать.</p>'),
            Field::make('select', 'openness', 'Открытость мероприятия')
                ->set_options(
                    [
                        ''       => '-- выберите значение из списка --',
                        'open'   => 'Общее',
                        'inner'  => 'Внутреннее'
                    ]
                ),
            Field::make('select', 'type', 'Тип мероприятия')
                ->set_options(
                    [
                        ''          => '-- выберите значение из списка --',
                        'online'    => 'Онлайн',
                        'default'   => 'Очное'
                    ]
                ),
            Field::make('select', 'event_cat', 'Категория мероприятия')
                ->set_options($event_cat),
            Field::make('select', 'event_form', 'Форма проведения мероприятия')
                ->set_options($event_form),
            Field::make('text', 'address', 'Адрес проведения')
                ->set_conditional_logic(
                    [
                        [
                            'field' => 'type',
                            'value' => 'default',
                            'compare' => '='
                        ]
                    ]
                ),
            Field::make('text', 'place', 'Место проведения')
                ->set_conditional_logic(
                    [
                        [
                            'field' => 'type',
                            'value' => 'default',
                            'compare' => '='
                        ]
                    ]
                ),
            Field::make('text', 'platform', 'Платформа проведения')
                ->set_conditional_logic(
                    [
                        [
                            'field' => 'type',
                            'value' => 'online',
                            'compare' => '='
                        ]
                    ]
                ),
            Field::make('text', 'link', 'Ссылка на трансляцию')
                ->set_conditional_logic(
                    [
                        [
                            'field' => 'type',
                            'value' => 'online',
                            'compare' => '='
                        ]
                    ]
                ),
            Field::make('text', 'password', 'Пароль трансляции')
                ->set_conditional_logic(
                    [
                        [
                            'field' => 'type',
                            'value' => 'online',
                            'compare' => '='
                        ]
                    ]
                ),
        ]
    );
