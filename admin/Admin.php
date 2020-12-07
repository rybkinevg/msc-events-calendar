<?php

class MSCEC_Admin
{
    // Название плагина
    private $plugin_name;

    // Актуальная версия плагина
    private $version;

    // Импортированный файл
    /**
     * @todo добписать getter функцию
     */
    private $imported_file_array;
    private $imported_file_url;

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    // Регистрирует тип записи Мероприятия
    public function register_post_types()
    {
        $events_args = [
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
        ];

        register_post_type(
            'events',
            $events_args
        );

        $events_organizers_args = [
            'label'  => null,
            'labels' => [
                'name'               => 'Организаторы', // основное название для типа записи
                'singular_name'      => 'Организатор', // название для одной записи этого типа
                'add_new'            => 'Добавить организатора', // для добавления новой записи
                'add_new_item'       => 'Добавление организатора', // заголовка у вновь создаваемой записи в админ-панели.
                'edit_item'          => 'Редактирование организатора', // для редактирования типа записи
                'new_item'           => 'Новый организатор', // текст новой записи
                'view_item'          => 'Смотреть организатора', // для просмотра записи этого типа.
                'search_items'       => 'Искать организатора', // для поиска по этим типам записи
                'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
                'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
                'parent_item_colon'  => '', // для родителей (у древовидных типов)
                'menu_name'          => 'Организаторы', // название меню
            ],
            'description'         => '',
            'public'              => true,
            'publicly_queryable'  => false,
            'show_in_menu'        => false,
            'show_in_rest'        => false,
            'rest_base'           => null,
            'menu_position'       => null,
            'hierarchical'        => false,
            'supports'            => ['title'],
            'taxonomies'          => [],
            'has_archive'         => true,
            'rewrite'             => true,
            'query_var'           => true,
        ];

        register_post_type(
            'events_organizers',
            $events_organizers_args
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
                    echo 'Общее';
                } else if ($type === 'inner') {
                    echo 'Внутреннее';
                }
                break;
            case 'organizer':
                $organizer = carbon_get_post_meta($post->ID, 'organizer');

                $query = new WP_Query(
                    [
                        'name'           => $organizer,
                        'post_type'      => 'events_organizers',
                        'posts_per_page' => -1
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
        include_once(MSCEC_DIR . 'admin/custom-fields/settings-page.php');
        include_once(MSCEC_DIR . 'admin/custom-fields/events-meta.php');
    }

    // Создаёт блок над таблицей вывода всех мероприятий для произвольного текста
    public function show_admin_stats($view)
    {
        include_once(MSCEC_DIR . 'admin/templates/admin-stats.php');
        return $view;
    }

    // Создаёт страницы внутри типа записи Мероприятия
    public function create_pages()
    {
        add_submenu_page(
            'edit.php?post_type=events',
            'Организаторы',
            'Организаторы',
            'manage_options',
            'edit.php?post_type=events_organizers'
        );

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
        include_once(MSCEC_DIR . 'admin/templates/import-page.php');
    }

    // Импорт мероприятий
    public function import_events_callback()
    {
        check_ajax_referer('import_events_nonce', 'nonce');

        $file = $_FILES['imported-events-file'];

        if (empty($file['name'])) wp_send_json_error('Файлов нет');

        if (!empty($file['error'])) wp_send_json_error('Произошла ошибка во время загрузки файла');

        if (!$this->check_mimes($file)) wp_send_json_error('Неверный тип файла');

        if (!function_exists('wp_handle_upload')) require_once(ABSPATH . 'wp-admin/includes/file.php');

        $overrides = ['test_form' => false];

        $movefile = wp_handle_upload($file, $overrides);
        $this->imported_file_url = $movefile['url'];
        $this->imported_file_array = $this->csv_to_array($movefile['url']);

        $html = $this->get_include_contents(MSCEC_DIR . 'admin/templates/template-parts/insert-form.php');

        wp_send_json_success($html);

        wp_die();
    }

    public function check_mimes($file)
    {
        $acceptable_mime_types = [
            'text/plain',
            'text/csv',
            'text/comma-separated-values'
        ];

        $tmp_name = $file['tmp_name'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $tmp_name);

        $file_mime_type = mime_content_type($tmp_name);

        return in_array($mime_type, $acceptable_mime_types) && in_array($file_mime_type, $acceptable_mime_types);
    }

    public function csv_to_array($csv_file)
    {
        $assoc_array = [];

        if (($handle = fopen($csv_file, "r")) !== false) {
            if (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $keys = $data;
            }
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $assoc_array[] = array_combine($keys, $data);
            }
            fclose($handle);

            return $assoc_array;
        } else {
            return false;
        }
    }

    public function get_include_contents($path)
    {
        ob_start();
        include $path;
        return ob_get_clean();
    }

    public function insert_events_callback()
    {
        check_ajax_referer('insert_events_nonce', 'nonce');

        $keys = $this->clear_post_data($_POST);

        if (empty($keys)) wp_send_json_error('Не выбраны импортируемые данные');

        $file_url = $_POST['file'];

        $insert_data = $this->sort_array($this->csv_to_array($file_url), $keys);

        $result = $this->insert($insert_data);

        if (isset($result['not_inserted']) && !isset($result['inserted'])) {
            wp_send_json_error($result['not_inserted']);
        }

        if (!isset($result['not_inserted']) && isset($result['inserted'])) {
            wp_send_json_success($result['inserted']);
        }

        wp_send_json_success($result['inserted'] . ', ' . $result['not_inserted']);
    }

    public function clear_post_data($post)
    {
        unset($post['action']);
        unset($post['nonce']);
        unset($post['file']);

        foreach ($post as $key => $value) {
            if ($value == 'unset') {
                unset($post[$key]);
            }
        }

        return $post;
    }

    public function sort_array($data, $keys)
    {
        for ($i = 0; $i < count($data); $i++) {
            foreach ($data[$i] as $key => $value) {
                $key_without_spaces = str_replace(" ", "_", $key); // ВАЖНО
                if (key_exists($key_without_spaces, $keys)) {
                    unset($data[$i][$key]);
                    $data[$i][$keys[$key_without_spaces]] = $value;
                } else {
                    unset($data[$i][$key]);
                }
            }
        }

        return $data;
    }

    public function insert($array)
    {
        $count_of_inserted = 0;
        $count_of_not_inserted = 0;
        $result = [];

        $required_keys = [
            'post_title',
            'post_content'
        ];

        $meta_keys = [
            'date',
            'time_start',
            'time_end',
            'address',
            'place',
            'platform',
            'link',
            'password'
        ];

        $specific_meta_keys = [
            'type',
            'organizer'
        ];

        foreach ($array as $key => $value) {
            if (is_null(get_page_by_title($value['post_title'], OBJECT, 'events'))) {
                $post_data = [
                    'post_type'     => 'events',
                    'post_title'    => $value['post_title'],
                    'post_content'  => $value['post_content'],
                    'post_status'   => 'publish',
                    'post_author'   => 1
                ];

                $post_id = wp_insert_post(wp_slash($post_data));

                $count_of_inserted++;

                if (!is_wp_error($post_id)) {
                    foreach ($meta_keys as $meta_key) {
                        if (isset($value[$meta_key])) {
                            update_post_meta($post_id, '_' . $meta_key, $value[$meta_key]);
                        }
                    }
                    foreach ($specific_meta_keys as $meta_key) {
                        if ($meta_key == 'type') {
                            if (isset($value[$meta_key])) {
                                if ($value[$meta_key] == 'Онлайн') {
                                    update_post_meta($post_id, '_' . $meta_key, 'online');
                                }
                                if ($value[$meta_key] == 'Общее') {
                                    update_post_meta($post_id, '_' . $meta_key, 'default');
                                }
                                if ($value[$meta_key] == 'Внутреннее') {
                                    update_post_meta($post_id, '_' . $meta_key, 'inner');
                                }
                            }
                        }
                        if ($meta_key == 'organizer') {

                            if (isset($value[$meta_key])) {

                                $organizer = get_page_by_title($value[$meta_key], OBJECT, 'events_organizers');

                                if ($organizer) {
                                    update_post_meta($post_id, '_' . $meta_key, $organizer->post_name);
                                }
                            }
                        }
                    }
                }
            } else {
                $count_of_not_inserted++;
            }
        }

        if ($count_of_inserted > 0) {
            $result['inserted'] = $count_of_inserted . ' мероприятий импортировано';
        }

        if ($count_of_not_inserted > 0) {
            $result['not_inserted'] = $count_of_not_inserted . ' мероприятий не импортировано из-за конфликта названия';
        }

        return $result;
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
            MSCEC_URL . 'admin/assets/css/style.css',
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
            MSCEC_URL . 'admin/assets/js/script.js',
            ['jquery'],
            $this->version,
            false
        );
    }
}
