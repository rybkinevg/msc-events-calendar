<?php get_header(); ?>

<link rel='stylesheet' id='fontAwesome-css' href='https://мойсемейныйцентр.москва/wp-content/themes/msc-theme/assets/lib/font-awesome/font-awesome.min.css?ver=5.5.1' type='text/css' media='all' />
<link rel="stylesheet" href="https://xn--e1aaancaqclcc7aew1d7d.xn--80adxhks/wp-content/themes/msc-theme/style.css?ver=5.5.1">

<main id="content" class="msc-events-calendar">
    <div class="container">
        <section class="content__inner">
            <div class="mscec-breadcrumbs">
                <a href="/events">
                    Назад
                </a>
            </div>
            <article class="post">
                <div class="row">

                    <?php

                    if (have_posts()) {

                        while (have_posts()) {

                            the_post();

                            $title = get_the_title();
                            $content = apply_filters('the_content', get_the_content());
                            $date = carbon_get_post_meta(get_the_ID(), 'date') ? date("d.m.Y", strtotime(carbon_get_post_meta(get_the_ID(), 'date'))) : '%%%';
                            $time = carbon_get_post_meta(get_the_ID(), 'time_start') . ' - ' . carbon_get_post_meta(get_the_ID(), 'time_end');
                            $type = carbon_get_post_meta(get_the_ID(), 'type');
                            $address = carbon_get_post_meta(get_the_ID(), 'address');
                            $place = carbon_get_post_meta(get_the_ID(), 'place');
                            $platform = carbon_get_post_meta(get_the_ID(), 'platform');
                            $link = carbon_get_post_meta(get_the_ID(), 'link');
                            $password = carbon_get_post_meta(get_the_ID(), 'password');

                            // Иногда ломает $post, поэтому вызывается последним
                            $organizer_slug = carbon_get_post_meta(get_the_ID(), 'organizer');
                            $organizer_name = MSCEC_Public::get_organizator_name($organizer_slug);
                            $organizer_link = MSCEC_Public::get_organizator_link($organizer_slug);

                    ?>

                            <div class="col col-content">
                                <h2 class="post__title post__header-title"><?= $title ?></h2>

                                <?php

                                if ($organizer_slug) {

                                ?>

                                    <div class="event__centr">
                                        <a href="<?= $organizer_link ?>" class="link link-orange">
                                            <i class="fa fa-building-o" aria-hidden="true"></i>
                                            <?= $organizer_name ?>
                                        </a>
                                    </div>

                                <?php

                                }

                                ?>

                                <div class="post__text">
                                    <?= $content ?>
                                </div>

                                <?php

                                if ($type === 'online') {

                                ?>

                                    <div class="mscec-type-online">
                                        <div class="mscec-type-online__item">
                                            <?= $platform ?>
                                        </div>

                                        <?php

                                        if ($link) {

                                        ?>

                                        <div class="mscec-type-online__item">
                                            <i class="fa fa-link" aria-hidden="true"></i>
                                            <a href="<?= $link ?>" target="_blank" class="link link-orange">Ссылка на трансляцию</a>
                                        </div>

                                        <?php

                                        }

                                        if ($password) {

                                        ?>

                                            <div class="mscec-type-online__item">
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                                <?= $password ?>
                                            </div>

                                        <?php

                                        }

                                        ?>

                                    </div>

                                <?php

                                }

                                ?>

                            </div>

                            <div class="col col-sidebar">
                                <aside class="sidebar-single-event border-top-orange">
                                    <div class="post__sidebar-inner">
                                        <h4 class="post__sidebar-title">
                                            <i class="sidebar__icon fa fa-info" aria-hidden="true"></i>
                                            <span class="sidebar__title">Информация о мероприятии</span>
                                        </h4>
                                        <ul class="event-info">
                                            <li class="list-item single-event__item">
                                                <i class="sidebar__icon fa fa-calendar-o" aria-hidden="true"></i>
                                                <span class="event-info__prop"><?= $date ?></span>
                                            </li>
                                            <li class="list-item single-event__item">
                                                <i class="sidebar__icon fa fa-clock-o" aria-hidden="true"></i>
                                                <span class="event-info__prop"><?= $time ?></span>
                                            </li>

                                            <?php
                                            if ($type === 'online') {
                                            ?>

                                                <li class="list-item single-event__item">
                                                    <i class="sidebar__icon fa fa-wifi" aria-hidden="true"></i>
                                                    <span class="event-info__prop">Онлайн мероприятие</span>
                                                </li>

                                                <?php

                                            } else {

                                                if ($place) {

                                                ?>

                                                    <li class="list-item single-event__item">
                                                        <i class="sidebar__icon fa fa-map-pin" aria-hidden="true"></i>
                                                        <span class="event-info__prop"><?= $place ?></span>
                                                    </li>

                                                <?php

                                                }

                                                if ($address) {

                                                ?>

                                                    <li class="list-item single-event__item">
                                                        <i class="sidebar__icon fa fa-map-marker" aria-hidden="true"></i>
                                                        <span class="event-info__prop"><?= $address ?></span>
                                                    </li>

                                            <?php

                                                }
                                            }

                                            ?>

                                        </ul>
                                    </div>
                                </aside>
                            </div>

                    <?php

                        }
                    } else {
                        echo "<h2>Мероприятия не найдено</h2>";
                    }

                    ?>

                </div>
                <footer class="post__footer">
                    <h2 class="post__title">
                        Ближайшие мероприятия
                    </h2>
                    <div class="post__relative-list">
                        <div class="row">
                            <ul class="ring">
                                <?php

                                $relative_events_args = [
                                    'post_type' => 'events',
                                    'posts_per_page' => 5,
                                    'post__not_in' => [get_the_ID()],
                                    'order' => 'ASC',
                                    'orderby' => 'events-date',
                                    'meta_query' => [
                                        'events-date' => [
                                            'key' => 'date',
                                            'value' => current_time('Y-m-d'),
                                            'compare' => '>='
                                        ]
                                    ]
                                ];

                                $query = new WP_Query($relative_events_args);

                                if ($query->have_posts()) {
                                    while ($query->have_posts()) {

                                        $query->the_post();

                                        $title = get_the_title();
                                        $link = wp_make_link_relative(get_permalink());
                                        $date = carbon_get_post_meta(get_the_ID(), 'date') ? date("d.m.Y", strtotime(carbon_get_post_meta(get_the_ID(), 'date'))) : '%%%';

                                ?>

                                        <li class="list-item">
                                            <span class="mscec-relative-link__date">
                                                <?= $date ?>
                                            </span>
                                            <a href="<?= $link ?>" class="link link-orange">
                                                <span>
                                                    <?= $title ?>
                                                </span>
                                            </a>
                                        </li>

                                <?php
                                    }
                                } else {
                                    echo 'Ничего не найдено';
                                }

                                wp_reset_postdata();

                                ?>
                            </ul>
                        </div>
                    </div>
                </footer>
            </article>
        </section>
    </div>
</main>

<?php get_footer(); ?>