<?php 

get_header();

$args = [
    'post_type'      => 'events',
    'posts_per_page' => 5,
    'post_status'    => 'publish',
    'paged' => 1,
    'meta_query'     => [
        'events-date' => [
            'key'     => '_date',
            'value'   => current_time('Y-m-d'),
            'type'    => 'DATE',
            'compare' => '>='
        ],
        'events-time' => [
            'key'     => '_time_start',
            'compare' => 'EXISTS',
            'type'    => 'TIME'
        ],
        'events-openness' => [
            'key'     => '_openness',
            'value'   => 'open',
            'compare' => '='
        ]
    ],
    'orderby'        => [
        'events-date' => 'ASC',
        'events-time' => 'ASC',
    ],
];

$query = new WP_Query($args);

?>

<link rel='stylesheet' id='fontAwesome-css' href='https://мойсемейныйцентр.москва/wp-content/themes/msc-theme/assets/lib/font-awesome/font-awesome.min.css?ver=5.5.1' type='text/css' media='all' />
<link rel="stylesheet" href="https://xn--e1aaancaqclcc7aew1d7d.xn--80adxhks/wp-content/themes/msc-theme/style.css?ver=5.5.1">

<main id="content" class="msc-events-calendar msc-events-archive">
    <div class="container">
        <section id="content__inner">
            <div class="events__content">
                <h2 class="page__title">Мероприятия</h2>
                <div class="row">
                    <div class="col col-content">
                        <div class="mscec-events">

                            <?php

                            // сделать блок с текстом - нашлось столько то мероприятий
                            // так же через $_GET отлавливать запрос даты и тд.
                            // вынести в loop весь кусок .col .col-content
                            // в JS сделать добавление в блок mscec-events (доп класс блока row)

                            if ($query->have_posts()) { ?>

                                <ul class="mscec-list list">

                                <?php require_once(MSCEC_DIR . 'public/templates/loop.php'); ?>

                                </ul>

                                <?php

                                if ($query->max_num_pages > 1) { ?>

                                    <script>
                                        var events_query = `<?= serialize($query->query_vars); ?>`;
                                        var current_page = <?= $query->query_vars['paged'] ?>;
                                        var max_pages = <?= $query->max_num_pages; ?>;
                                    </script>

                                <?php

                                    if ($args['paged'] === 1) {

                                        echo "<button id='true_loadmore' class='btn btn-orange'>Загрузить ещё</button>";
                                    }
                                }

                            } else { ?>

                                <div class="empty-block">
                                    <img class="img empty-block__img" src="<?= MSCEC_URL . 'public/assets/img/events-empty.svg' ?>">
                                    <h2 class="empty-block__title">Ой! Похоже ничего не найдено.</h2>
                                    <p class="empty-block__text">Попробуйте выбрать другую дату.</p>
                                </div>
                            
                            <?php
                            
                            }
                            
                            wp_reset_postdata(); ?>
                        
                        </div>
                    </div>
                    <div class="col col-sidebar">
                        <aside class="sidebar">
                            <div class="sidebar__item border-top-orange sidebar__calendar">
                                <div class="mscec-loader">
                                    <div class="sk-fading-circle">
                                        <div class="sk-circle sk-circle-1"></div>
                                        <div class="sk-circle sk-circle-2"></div>
                                        <div class="sk-circle sk-circle-3"></div>
                                        <div class="sk-circle sk-circle-4"></div>
                                        <div class="sk-circle sk-circle-5"></div>
                                        <div class="sk-circle sk-circle-6"></div>
                                        <div class="sk-circle sk-circle-7"></div>
                                        <div class="sk-circle sk-circle-8"></div>
                                        <div class="sk-circle sk-circle-9"></div>
                                        <div class="sk-circle sk-circle-10"></div>
                                        <div class="sk-circle sk-circle-11"></div>
                                        <div class="sk-circle sk-circle-12"></div>
                                    </div>
                                </div>
                                <div class="post__sidebar-inner">
                                    <h4 class="post__sidebar-title">
                                        <i class="sidebar__icon fa fa-calendar-check-o" aria-hidden="true"></i>
                                        <span class="sidebar__title">Календарь мероприятий</span>
                                        <button class="mscec-sidebar-spoiler">
                                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                        </button>
                                    </h4>
                                    <div class="mscec-controll">
                                        <div class="mscec-controll__inner">
                                            <div id="mscec-datepicker" class='mscec-datepicker'>
                                                <input class="events_calendar_nonce" type="hidden" name="nonce" value="<?= wp_create_nonce('events_calendar_nonce') ?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="sidebar__item border-top-orange sidebar__filter">
                                <div class="mscec-loader">
                                    <div class="sk-fading-circle">
                                        <div class="sk-circle sk-circle-1"></div>
                                        <div class="sk-circle sk-circle-2"></div>
                                        <div class="sk-circle sk-circle-3"></div>
                                        <div class="sk-circle sk-circle-4"></div>
                                        <div class="sk-circle sk-circle-5"></div>
                                        <div class="sk-circle sk-circle-6"></div>
                                        <div class="sk-circle sk-circle-7"></div>
                                        <div class="sk-circle sk-circle-8"></div>
                                        <div class="sk-circle sk-circle-9"></div>
                                        <div class="sk-circle sk-circle-10"></div>
                                        <div class="sk-circle sk-circle-11"></div>
                                        <div class="sk-circle sk-circle-12"></div>
                                    </div>
                                </div>
                                <div class="post__sidebar-inner">
                                    <h4 class="post__sidebar-title">
                                        <i class="sidebar__icon fa fa-filter" aria-hidden="true"></i>
                                        <span class="sidebar__title">Фильтр мероприятий</span>
                                        <button class="mscec-sidebar-spoiler">
                                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                        </button>
                                    </h4>
                                    <div class="mscec-controll">
                                        <div class="mscec-controll__inner">
                                            <form id="mscec-filter">
                                                <input type="hidden" name="action" value="events_filter" />
                                                <input class="events_filter_nonce" type="hidden" name="nonce" value="<?= wp_create_nonce('events_filter_nonce') ?>" />
                                                <div class="mscec-form__item">
                                                    <label class="mscec-form__label" for="mscec-filter__title">
                                                        Название
                                                    </label>
                                                    <input id="mscec-filter__title" class="mscec-form__input" type="search" name="events_title" autocomplete="off" />
                                                </div>
                                                <div class="mscec-form__item">
                                                    <label class="mscec-form__label" for="mscec-filter__date">
                                                        Дата
                                                    </label>
                                                    <input id="mscec-filter__date" type="text" class="mscec-form__input" name="events_date" autocomplete="off" />
                                                </div>
                                                <div class="mscec-form__item">
                                                    <label class="mscec-form__label" for="mscec-filter__organizer">
                                                        Организатор
                                                    </label>
                                                    <select id="mscec-filter__organizer" name="events_organizer">
                                                        <option value="">Все</option>
                                                        <?php

                                                        $organizers = get_posts(
                                                            [
                                                                'numberposts' => -1,
                                                                'post_type'   => 'events_organizers',
                                                                'post_status' => 'publish',
                                                                'order' => 'ASC'
                                                            ]
                                                        );

                                                        if ($organizers) {
                                                            foreach ($organizers as $organizer) {
                                                                echo "<option value='{$organizer->post_name}'>{$organizer->post_title}</option>";
                                                            }
                                                        }

                                                        wp_reset_postdata();

                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="mscec-form__item">
                                                    <label class="mscec-form__label">
                                                        Тип
                                                    </label>
                                                    <div class="form__setting">
                                                        <label class="form__label">
                                                            <input class="form__radio" type="radio" name="events_type" value="" hidden="" checked><span class="form__custom-radio"></span>
                                                            <span class="form__label-text">Все</span>
                                                        </label>
                                                        <label class="form__label">
                                                            <input class="form__radio" type="radio" name="events_type" value="online" hidden=""><span class="form__custom-radio"></span>
                                                            <span class="form__label-text">Онлайн</span>
                                                        </label>
                                                        <label class="form__label">
                                                            <input class="form__radio" type="radio" name="events_type" value="default" hidden=""><span class="form__custom-radio"></span>
                                                            <span class="form__label-text">Очные</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mscec-form__item">
                                                    <button class="btn btn-orange" type="submit">Искать</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<?php get_footer(); ?>