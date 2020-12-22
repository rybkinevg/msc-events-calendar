<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('post_meta', 'Информация об организаторе')
    ->where('post_type', '=', 'events_organizers')
    ->add_fields(
        [
            Field::make('text', 'name', 'Полное имя организатора'),
            Field::make('text', 'link', 'Ссылка на организатора')
        ]
    );
