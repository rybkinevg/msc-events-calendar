<?php

get_header();

$args = [
    'post_type' => 'events',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'order' => 'ASC'
];

$query = new WP_Query($args);

?>


<link rel='stylesheet' id='fontAwesome-css' href='https://мойсемейныйцентр.москва/wp-content/themes/msc-theme/assets/lib/font-awesome/font-awesome.min.css?ver=5.5.1' type='text/css' media='all' />
<link rel="stylesheet" href="https://xn--e1aaancaqclcc7aew1d7d.xn--80adxhks/wp-content/themes/msc-theme/style.css?ver=5.5.1">

<main id="content" class="msc-events-calendar">
    <div class="container">
        <section id="content__inner">
            <div class="events__content">
                <h2 class="page__title">Мероприятия</h2>
                <div class="row">
                    <div class="col col-content">
                        <ul class="mscec-list list">
                            <?php require_once(MSCEC_DIR . 'public/templates/loop.php') ?>
                        </ul>
                    </div>
                    <div class="col col-sidebar">
                        <aside class="post__sidebar border-top-orange sidebar-sticky">
                            <div class="post__sidebar-inner">
                                <h4 class="post__sidebar-title">
                                    <i class="sidebar__icon fa fa-info" aria-hidden="true"></i>
                                    <span class="sidebar__title">Календарь мероприятий</span>
                                </h4>
                                <div class="mscec-controll">
                                    <div class="mscec-controll__inner">
                                        <form id="mscec-filter">
                                            <input type="hidden" name="action" value="events_filter" />
                                            <input class="events_filter_nonce" type="hidden" name="nonce" value="<?= wp_create_nonce('events_filter_nonce') ?>" />
                                            <div class="mscec-form__item">
                                                <input class="mscec-form__input" type="search" name="events_title" autocomplete="off" placeholder="Поиск по названию">
                                            </div>
                                            <div class="mscec-form__item">
                                                <select name="events_organizer">
                                                    <option value="">Организаторы</option>
                                                    <option value="otradnoe">Отрадное</option>
                                                    <option value="krizis-centr">Кризисный</option>
                                                    <option value="altufevo">Алтуфьево</option>
                                                </select>
                                            </div>
                                            <div class="mscec-form__item">
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
                                                <input type="submit" value="Искать">
                                            </div>
                                            <div id="mscec-datepicker" class='mscec-datepicker'></div>
                                        </form>
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