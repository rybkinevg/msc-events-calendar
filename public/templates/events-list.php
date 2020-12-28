<div class="">
<?php var_dump($query->meta_query->queries['events-date']['value']) ?>
    <div class="mscec-events__found">
        Найдено: <?= $query->found_posts ?>
    </div>
</div>
<div class="">
    <div class="mscec-events__">

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