<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('theme_options', 'Настройки мероприятий')
    ->set_page_parent('edit.php?post_type=events')
    ->set_page_menu_title('Настройки')
    ->set_page_file('options')
    ->add_tab(
        'Общие настройки',
        [
            Field::make('html', 'dev_1', 'Раздел в разработке')
                ->set_html('<p>*стрекот сверчков*</p>')
        ]
    )
    ->add_tab(
        'Организаторы',
        [
            Field::make('html', 'dev_2', 'Раздел в разработке')
                ->set_html('<p>*стрекот сверчков*</p>')
        ]
    );
