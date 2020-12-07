<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * @todo добавить динамические переменные: тип записи, название организатора, ссылка или перенести в метод класса
 */
// Получает список организаторов
function get_organizators()
{
    global $post;

    $args = [
        'post_type' => 'events_organizers',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ];

    $query = new WP_Query($args);

    $organizers = [
        'not-selected' => 'Выберите организатора'
    ];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $organizers[$post->post_name] = get_the_title();
        }
    } else {
        $organizers['null'] = 'Организаторов не найдено';
    }

    return $organizers;
}

Container::make('post_meta', 'Информация о мероприятии')
    ->where('post_type', '=', 'events')
    ->add_fields(
        [
            Field::make('date', 'date', 'Дата мероприятия'),
            Field::make('time', 'time_start', 'Время начала')
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
                ->set_options('get_organizators'),
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
            Field::make('select', 'type', 'Тип мероприятия')
                ->set_options(
                    [
                        'default' => 'Общее',
                        'online'  => 'Онлайн',
                        'inner'   => 'Внутреннее'
                    ]
                ),
            Field::make('text', 'address', 'Адрес проведения')
                ->set_conditional_logic(
                    [
                        'relation' => 'OR',
                        [
                            'field' => 'type',
                            'value' => 'default',
                            'compare' => '='
                        ],
                        [
                            'field' => 'type',
                            'value' => 'inner',
                            'compare' => '='
                        ]
                    ]
                ),
            Field::make('text', 'place', 'Место проведения')
                ->set_conditional_logic(
                    [
                        'relation' => 'OR',
                        [
                            'field' => 'type',
                            'value' => 'default',
                            'compare' => '='
                        ],
                        [
                            'field' => 'type',
                            'value' => 'inner',
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
