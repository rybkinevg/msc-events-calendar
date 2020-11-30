<?php

class MSCEC_Admin
{
    // Название плагина
    private $plugin_name;

    // Актуальная версия плагина
    private $version;

    // Путь до файла
    private $dir = MSCEC_DIR . 'admin/';

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    // Регистрирует тип записи Мероприятия
    public function register_post_types()
    {
        register_post_type(
            'events',
            [
                'label'  => null,
                'labels' => [
                    'name'               => 'Мероприятия', // основное название для типа записи
                    'singular_name'      => 'Мероприятие', // название для одной записи этого типа
                    'add_new'            => 'Добавить мероприятие', // для добавления новой записи
                    'add_new_item'       => 'Добавление мероприятия', // заголовка у вновь создаваемой записи в админ-панели.
                    'edit_item'          => 'Редактирование мероприятия', // для редактирования типа записи
                    'new_item'           => 'Новое мероприятие', // текст новой записи
                    'view_item'          => 'Смотреть мероприятие', // для просмотра записи этого типа.
                    'search_items'       => 'Искать мероприятие', // для поиска по этим типам записи
                    'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
                    'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
                    'parent_item_colon'  => '', // для родителей (у древовидных типов)
                    'menu_name'          => 'Мероприятия', // название меню
                ],
                'description'         => '',
                'public'              => true,
                'show_in_menu'        => null,
                'show_in_rest'        => null,
                'rest_base'           => null,
                'menu_position'       => null,
                'menu_icon'           => null,
                'hierarchical'        => false,
                'supports'            => ['title', 'editor'],
                'taxonomies'          => [],
                'has_archive'         => false,
                'rewrite'             => true,
                'query_var'           => true,
            ]
        );
    }

    // Создание новых колонок в таблице вывода мероприятий
    public function events_add_columns($columns)
    {
        unset($columns['date']);

        $columns['event_date'] = 'Дата';
        $columns['type']       = 'Тип';
        $columns['organizer']  = 'Организатор';
        $columns['date']  = 'Дата создания';

        return $columns;
    }

    // Заполнение новых колонок в таблице вывода мероприятий
    public function events_fill_columns($columns)
    {
        global $post;
        switch ($columns) {
            case 'event_date':
                $date = carbon_get_post_meta($post->ID, 'date');
                $date = date("d.m.Y", strtotime($date));
                echo $date;
                break;
            case 'type':
                $type = carbon_get_post_meta($post->ID, 'type');
                if ($type === 'online') {
                    echo 'Онлайн';
                } else if ($type === 'default') {
                    echo 'Общие';
                } else if ($type === 'inner') {
                    echo 'Внутренние';
                }
                break;
            case 'organizer':
                $organizer = carbon_get_post_meta($post->ID, 'organizer');

                $query = new WP_Query(
                    [
                        'name'      => $organizer,
                        'post_type' => 'centres'
                    ]
                );

                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                        echo get_the_title();
                    }
                } else {
                    echo 'Не выбран';
                }
                wp_reset_postdata();
                break;
        }
    }

    // Дополнительные поля, созданные с помощью плагина Carbon Fields
    public function create_custom_fields()
    {
        include_once($this->dir . 'custom-fields/settings-page.php');
        include_once($this->dir . 'custom-fields/events-meta.php');
    }

    // Создаёт блок над таблицей вывода всех мероприятий для произвольного текста
    public function show_admin_stats($view)
    {
        include_once($this->dir . 'templates/admin-stats.php');
        return $view;
    }

    // Создаёт страницы внутри типа записи Мероприятия
    public function create_pages()
    {
        add_submenu_page(
            'edit.php?post_type=events',
            'Импорт мероприятий',
            'Импорт',
            'manage_options',
            'import',
            [$this, 'import_page']
        );
    }

    // Внешний вид страницы импорта мероприятий
    public function import_page()
    {
        include_once($this->dir . 'templates/import-page.php');
    }

    // Импорт мероприятий
    public function import_events()
    {
        check_ajax_referer('import_events', 'nonce');
    }

    /**
     * @todo сделать форму-фильтр для организаторов
     */
    public function events_admin_filter($post_type)
    {
        if ('events' !== $post_type) return;

        echo '
        <select name="events_type">
            <option value="all">Все типы</option>
            <option value="default"' . selected('default', @$_GET['events_type'], 0) . '>Общие</option>
            <option value="online"' .   selected('online', @$_GET['events_type'], 0) . '>Онлайн</option>
            <option value="inner"' .  selected('inner', @$_GET['events_type'], 0) . '>Внутренние</option>
        </select>';
    }

    /**
     * @todo сделать фильтр по организаторам
     */
    public function events_admin_filter_handler($query)
    {
        $cs = function_exists('get_current_screen') ? get_current_screen() : null;

        if (!is_admin() || empty($cs->post_type) || $cs->post_type != 'events' || $cs->id != 'edit-events')
            return;

        if (@$_GET['events_type'] != 'all' && !empty(@$_GET['events_type'])) {
            $selected_id = @$_GET['events_type'] ?: 20;
            $query->set(
                'meta_query',
                [
                    [
                        'key'     => '_type',
                        'value'   => $selected_id,
                        'compare' => '='
                    ]
                ]
            );
        }

        //if( empty($_GET['orderby']) && @ $_GET['sel_season'] != -1 ){
        //  $query->set( 'orderby', 'menu_order date' );
        //}
    }

    // Регистрирует стили
    public function enqueue_styles()
    {
        wp_enqueue_style(
            $this->plugin_name,
            $this->dir . 'css/plugin-name-admin.css',
            [],
            $this->version,
            'all'
        );
    }

    // Регистрирует скрипты
    public function enqueue_scripts()
    {
        wp_enqueue_script(
            $this->plugin_name,
            $this->dir . 'js/plugin-name-admin.js',
            ['jquery'],
            $this->version,
            false
        );
    }
}
