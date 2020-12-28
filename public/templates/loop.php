<?php

if ($query->have_posts()) {

    if ($args['paged'] === 1) {

        $class = ($events_main) ? 'hide' : '';

        $events_titles = [
            '%d мероприятие',
            '%d мероприятия',
            '%d мероприятий'
        ];

        $date = isset($_GET['events_date']) ? $_GET['events_date'] : null;

        $count = MSCEC_Public::declOfNum($query->found_posts, $events_titles);

        $type = isset($_GET['events_type']) ? $_GET['events_type'] : null;

        $organizer = isset($_GET['events_organizer']) ? MSCEC_Public::get_organizator_name($_GET['events_organizer']) : null;

        $search = isset($_GET['events_title']) ? $_GET['events_title'] : null;

        ?>

            <div class='mscec-query__info <?= $class ?>'>
                <div class="mscec-query__count">
                    <i class="fa fa-calendar-check-o sidebar-icon" aria-hidden="true"></i>
                    <span class="mscec-query__text">Найдено <?= $count ?></span>
                    <button class="mscec-sidebar-spoiler active">
                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="mscec-query__inner">

                    <?php
                    
                    if ($date) {

                    ?>

                    <div class='mscec-query__item'>
                        Выбранная дата <?= $date ?>
                    </div>

                    <?php

                    }

                    if ($type) {

                        $type = ($type == 'online') ? 'онлайн' : 'очные';

                        ?>
        
                        <div class='mscec-query__item'>
                            Тип мероприятий <?= $type ?>
                        </div>
        
                        <?php
                        
                    }

                    if ($organizer) {

                        ?>
        
                        <div class='mscec-query__item'>
                            Организатор <?= $organizer ?>
                        </div>
        
                        <?php
        
                    }

                    if ($search) {

                        ?>
        
                        <div class='mscec-query__item'>
                            Поисковый запрос <?= $search ?>
                        </div>
        
                        <?php
        
                    }
                    
                    ?>
                </div>
            </div>

        <?php
    }

?>

    <ul class="mscec-list list">

        <?php

        while ($query->have_posts()) {
            $query->the_post();

            $title = get_the_title();
            $date = carbon_get_post_meta(get_the_ID(), 'date') ? date("d.m.Y", strtotime(carbon_get_post_meta(get_the_ID(), 'date'))) : '%%%';
            $time = carbon_get_post_meta(get_the_ID(), 'time_start') . ' - ' . carbon_get_post_meta(get_the_ID(), 'time_end');
            $type = carbon_get_post_meta(get_the_ID(), 'type');
            $link = wp_make_link_relative(get_permalink());

            // Иногда ломает $post, поэтому вызывается последним
            $organizer_slug = carbon_get_post_meta(get_the_ID(), 'organizer');
            $organizer_name = MSCEC_Public::get_organizator_name($organizer_slug);

        ?>
            <li class="mscec-list-item mscec-event list-item border-top-orange">
                <div class="mscec-event__info">
                    <span class="mscec-event__info-item mscec-event__date">
                        <i class="fa fa-calendar-o" aria-hidden="true"></i>
                        <?= $date ?>
                    </span>
                    <span class="mscec-event__info-item mscec-event__time">
                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                        <?= $time ?>
                    </span>
                    <?php if ($type === 'online') : ?>
                        <span class="mscec-event__info-item mscec-event__type">
                            <i class="fa fa-wifi" aria-hidden="true"></i>
                            Онлайн
                        </span>
                    <?php else : ?>
                        <span class="mscec-event__info-item mscec-event__type">
                            <i class="fa fa-university" aria-hidden="true"></i>
                            Очное
                        </span>
                    <?php endif; ?>
                </div>
                <div class="mscec-event__content">
                    <h3 class="mscec-event__title">
                        <a href="<?= $link ?>" class="link link-orange"><?= $title ?></a>
                    </h3>

                    <?php

                    if ($organizer_name) {

                    ?>

                        <span class="mscec-event__organizer">
                            <i class="fa fa-building-o" aria-hidden="true"></i>
                            <?= $organizer_name ?>
                        </span>

                    <?php

                    }

                    ?>

                </div>
            </li>

        <?php

        }

        ?>

    </ul>

    <?php

    if ($query->max_num_pages > 1) {

    ?>

        <script>
            var events_query = `<?= serialize($query->query_vars); ?>`;
            var current_page = <?= $query->query_vars['paged'] ?>;
            var max_pages = <?= $query->max_num_pages; ?>;
        </script>

    <?php

        if ($args['paged'] === 1) {

            echo "<button class='mscec-loadmore-btn btn btn-orange'>Загрузить ещё</button>";
        }
    }
} else {

    ?>
    <div class="empty-block">
        <img class="img empty-block__img" src="<?= MSCEC_URL . 'public/assets/img/events-empty.svg' ?>">
        <h2 class="empty-block__title">Ой! Похоже ничего не найдено.</h2>
        <p class="empty-block__text">Попробуйте выбрать другую дату.</p>
    </div>

<?php

}

wp_reset_postdata();

?>