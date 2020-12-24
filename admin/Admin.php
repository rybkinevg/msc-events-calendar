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
    private $imported_file_path;

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
            'menu_icon'           => 'dashicons-calendar-alt',
            'hierarchical'        => false,
            'supports'            => ['title', 'editor'],
            'taxonomies'          => [],
            'has_archive'         => true,
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
            'has_archive'         => false,
            'rewrite'             => true,
            'query_var'           => false,
        ];

        register_post_type(
            'events_organizers',
            $events_organizers_args
        );

        flush_rewrite_rules();
    }

    // Создание новых колонок в таблице вывода мероприятий
    public function events_add_columns($columns)
    {
        unset($columns['date']);

        $columns['event_date'] = 'Дата';
        $columns['openness']   = 'Открытость';
        $columns['type']       = 'Тип';
        $columns['organizer']  = 'Организатор';
        $columns['date']       = 'Дата создания';

        return $columns;
    }

    // Заполнение новых колонок в таблице вывода мероприятий
    public function events_fill_columns($columns)
    {
        global $post;
        switch ($columns) {
            case 'event_date':
                $date = carbon_get_post_meta($post->ID, 'date');

                if ($date) {
                    $date = date("d.m.Y", strtotime($date));
                    echo $date;
                } else {
                    echo '-';
                }

                break;
            case 'type':
                $type = carbon_get_post_meta($post->ID, 'type');

                if ($type) {
                    if ($type === 'online') {
                        echo 'Онлайн';
                    } else {
                        echo 'Очное';
                    }
                } else {
                    echo '-';
                }

                break;
            case 'openness':
                $openness = carbon_get_post_meta($post->ID, 'openness');

                if ($openness) {
                    if ($openness === 'open') {
                        echo 'Общее';
                    } else {
                        echo 'Внутреннее';
                    }
                } else {
                    echo '-';
                }

                break;
            case 'organizer':
                $organizer = carbon_get_post_meta($post->ID, 'organizer');

                if ($organizer) {

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
                    }

                    wp_reset_postdata();
                } else {
                    echo '-';
                }

                break;
        }
    }

    // Дополнительные поля, созданные с помощью плагина Carbon Fields
    public function create_custom_fields()
    {
        include_once(MSCEC_DIR . 'admin/custom-fields/settings-page.php');
        include_once(MSCEC_DIR . 'admin/custom-fields/events-meta.php');
        include_once(MSCEC_DIR . 'admin/custom-fields/organizers-meta.php');
    }

    // Создаёт блок над таблицей вывода всех мероприятий для произвольного текста
    public function show_events_archive_link($view)
    {
        include_once(MSCEC_DIR . 'admin/templates/admin-events-archive-link.php');
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

        add_filter( 'upload_dir', [$this, 'upload_file'] );
        $movefile = wp_handle_upload($file, $overrides);
        remove_filter( 'upload_dir', [$this, 'upload_file'] );

        $this->imported_file_path = $movefile['file'];

        $this->imported_file_array = $this->csv_to_array($this->imported_file_path);

        $html = $this->get_include_contents(MSCEC_DIR . 'admin/templates/template-parts/insert-form.php');

        wp_send_json_success($html);

        wp_die();
    }

    public function upload_file($upload)
    {
        $upload['basedir'] = MSCEC_DIR;
        $upload['baseurl'] = MSCEC_URL;
        $upload['subdir'] = 'imports';
        $upload['url']  = $upload['baseurl'] . $upload['subdir'];
        $upload['path'] = $upload['basedir'] . $upload['subdir'];

        return $upload;
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

    public function csv_to_array($csv_file, $separator = ';')
    {
        $assoc_array = [];

        if (($handle = fopen($csv_file, "r")) !== false) {
            if (($data = fgetcsv($handle, 10000, $separator)) !== false) {
                $keys = $data;
            }
            while (($data = fgetcsv($handle, 10000, $separator)) !== false) {
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

        if (!in_array('post_title', $keys)) wp_send_json_error('Не выбраны названия мероприятий');

        if (!in_array('post_content', $keys)) wp_send_json_error('Не выбраны описания мероприятий');

        $file_url = $_POST['file'];

        $insert_data = $this->sort_array($this->csv_to_array($file_url), $keys);

        $insert_result = $this->insert($insert_data);

        $events_titles = [
            '%d мероприятие',
            '%d мероприятия',
            '%d мероприятий'
        ];

        /**
         * Если не импортировано ни одного мероприятия
         */
        if (isset($insert_result['not_inserted']) && !isset($insert_result['inserted'])) {

            $not_inserted_num = $insert_result['not_inserted'];
            $result = 'Не импортировано ' . $this->declOfNum($not_inserted_num, $events_titles);

            wp_send_json_error($result);
        }

        /**
         * Если импортированы все мероприятия
         */
        if (!isset($insert_result['not_inserted']) && isset($insert_result['inserted'])) {

            $inserted_num = $insert_result['inserted'];
            $result = 'Импортировано ' . $this->declOfNum($inserted_num, $events_titles);

            $this->add_to_history(basename($file_url), $file_url, date("Y-m-d"), current_time('H:i'), $result);

            wp_send_json_success($result);
        }

        /**
         * Если есть импортированные и неимпортированные мероприятия
         */
        if (isset($insert_result['not_inserted']) && isset($insert_result['inserted'])) {

            $inserted_num = $insert_result['inserted'];
            $not_inserted_num = $insert_result['not_inserted'];
            $result = 'Импортировано ' . $this->declOfNum($inserted_num, $events_titles) . ', не импортировано ' . $this->declOfNum($not_inserted_num, $events_titles);

            $this->add_to_history(basename($file_url), $file_url, date("Y-m-d"), current_time('H:i'), $result);

            wp_send_json_success($result);
        }
    }

    public function declOfNum($number, $titles)
    {
        $cases = array(2, 0, 1, 1, 1, 2);
        $format = $titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
        return sprintf($format, $number);
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
            'openness',
            'organizer'
        ];

        foreach ($array as $key => $value) {

            $post_data = [
                'post_type'     => 'events',
                'post_title'    => $value['post_title'],
                'post_content'  => $value['post_content'],
                'post_status'   => 'publish',
                'post_author'   => 1
            ];

            $post_id = wp_insert_post(wp_slash($post_data));

            if (!is_wp_error($post_id)) {

                $count_of_inserted++;

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

                            if ($value[$meta_key] == 'Очное') {
                                update_post_meta($post_id, '_' . $meta_key, 'default');
                            }
                        }
                    }
                    if ($meta_key == 'openness') {
                        if (isset($value[$meta_key])) {

                            if ($value[$meta_key] == 'Общее') {
                                update_post_meta($post_id, '_' . $meta_key, 'open');
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
            } else {
                $count_of_not_inserted++;
            }
        }

        if ($count_of_inserted > 0) {
            $result['inserted'] = $count_of_inserted;
        }

        if ($count_of_not_inserted > 0) {
            $result['not_inserted'] = $count_of_not_inserted;
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
            <option value="default"' . selected('default', @$_GET['events_type'], 0) . '>Очные</option>
            <option value="online"' .   selected('online', @$_GET['events_type'], 0) . '>Онлайн</option>
        </select>';

        echo '
        <select name="events_openness">
            <option value="all">Общие и внутренние</option>
            <option value="open"' . selected('open', @$_GET['events_openness'], 0) . '>Общие</option>
            <option value="inner"' .   selected('inner', @$_GET['events_openness'], 0) . '>Внутренние</option>
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

        if (@$_GET['events_openness'] != 'all' && !empty(@$_GET['events_openness'])) {
            $selected_id = @$_GET['events_openness'] ?: 20;
            $query->set(
                'meta_query',
                [
                    [
                        'key'     => '_openness',
                        'value'   => $selected_id,
                        'compare' => '='
                    ]
                ]
            );
        }
    }

    /**
     * Работа с базой
     * Функция отвечает за добавление файла в историю импортов
     */
    public function add_to_history($name, $file, $date, $time, $count)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'mscec_imports';

        $user_info = get_userdata(get_current_user_id());

        $wpdb->insert(
            $table_name,
            [
                'name' => $name,
                'date' => $date,
                'time' => $time,
                'count' => $count,
                'file' => $file,
                'user' => $user_info->first_name . ' ' . $user_info->last_name
            ],
            [
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            ]
        );
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
            'double-scroll',
            MSCEC_URL . 'admin/assets/lib/double-scroll/double-scroll.min.js',
            ['jquery'],
            $this->version,
            false
        );

        wp_enqueue_script(
            $this->plugin_name,
            MSCEC_URL . 'admin/assets/js/script.js',
            ['jquery', 'double-scroll'],
            $this->version,
            false
        );
    }
}
