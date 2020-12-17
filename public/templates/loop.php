<?php

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();

        $title = get_the_title();
        $date = carbon_get_post_meta(get_the_ID(), 'date') ? date("d.m.Y", strtotime(carbon_get_post_meta(get_the_ID(), 'date'))) : '%%%';
        $time = carbon_get_post_meta(get_the_ID(), 'time_start') . ' - ' . carbon_get_post_meta(get_the_ID(), 'time_end');
        $type = carbon_get_post_meta(get_the_ID(), 'type');
        $link = wp_make_link_relative(get_permalink());

        // Иногда ломает $post, поэтому вызывается последним
        $organizer = carbon_get_post_meta(get_the_ID(), 'organizer');
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
                    <a href="<?= $link ?>"><?= $title ?></a>
                </h3>
                <span class="mscec-event__organizer">
                    <i class="fa fa-building-o" aria-hidden="true"></i>
                    <?= $organizer ?>
                </span>
            </div>
        </li>
    <?php
    }
} else {
    ?>
    <p>Мероприятий не найдено</p>
<?php
}
wp_reset_postdata();
?>