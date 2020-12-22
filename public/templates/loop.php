<?php

$query = new WP_Query($args);

if ($query->have_posts()) {

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

            echo "<button id='true_loadmore'>Загрузить ещё</button>";
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