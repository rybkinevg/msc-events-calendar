<?php

class MSCEC_Core
{
    // Специальный класс загрузчика всех хуков
    protected $loader;

    // Название плагина
    protected $plugin_name;

    // Версия плагина
    protected $version;

    public function __construct()
    {
        if (defined('MSCEC_VERSION')) {
            $this->version = MSCEC_VERSION;
        } else {
            $this->version = '1.0.0';
        }

        $this->plugin_name = 'msc-events-calendar';

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies()
    {
        // Добавления зависимостей от сторонних плагинов WordPress
        require_once(MSCEC_DIR . 'includes/TGM/class-tgm-plugin-activation.php');
        add_action('tgmpa_register', [$this, 'required_plugins']);

        // Специальный класс загрузчик
        require_once(MSCEC_DIR . 'includes/Loader.php');

        $this->loader = new MSCEC_Loader();

        // Административная часть
        require_once(MSCEC_DIR . 'admin/Admin.php');

        // Публичная часть
        require_once(MSCEC_DIR . 'public/Public.php');
    }

    public function required_plugins()
    {
        $plugins = [
            [
                'name'               => 'Carbon Fields',
                'slug'               => 'carbon-fields',
                'source'             => 'https://carbonfields.net/zip/latest/',
                'required'           => true, // this plugin is required
                'external_url'       => 'https://carbonfields.net/',
                'force_deactivation' => true, // deactivate this plugin when the user switches to another theme
            ]
        ];

        $config = [
            'id'           => 'msc-events-calendar',
            'default_path' => '',
            'menu'         => 'tgmpa-install-plugins',
            'parent_slug'  => 'plugins.php',
            'capability'   => 'manage_options',
            'has_notices'  => true,
            'dismissable'  => true,
            'dismiss_msg'  => '',
            'is_automatic' => false,
            'message'      => '',
            'strings'      => [
                'notice_can_install_required' => _n_noop(
                    'Для корректной работы темы необходимо установить плагин: %1$s.',
                    'Для корректной работы темы необходимо установить плагины: %1$s.',
                    'msc-events-calendar'
                ),
                'install_link'                => _n_noop(
                    'Установить плагин',
                    'Установить плагины',
                    'msc-events-calendar'
                ),
            ]
        ];

        tgmpa($plugins, $config);
    }

    // Регистрация хуков административной части
    private function define_admin_hooks()
    {
        $plugin_admin = new MSCEC_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('init', $plugin_admin, 'register_post_types');
        $this->loader->add_action('carbon_fields_register_fields', $plugin_admin, 'create_custom_fields');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'create_pages');

        $this->loader->add_action('wp_ajax_import_events_action', $plugin_admin, 'import_events_callback');

        $this->loader->add_action('restrict_manage_posts', $plugin_admin, 'events_admin_filter');
        $this->loader->add_action('pre_get_posts', $plugin_admin, 'events_admin_filter_handler');

        $this->loader->add_filter('manage_edit-events_columns', $plugin_admin, 'events_add_columns');
        $this->loader->add_action('manage_posts_custom_column', $plugin_admin, 'events_fill_columns');

        $this->loader->add_filter('views_edit-events', $plugin_admin, 'show_admin_stats');
    }

    // Регистрация хуков публичной части
    private function define_public_hooks()
    {
        $plugin_public = new MSCEC_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        $this->loader->add_filter('template_include', $plugin_public, 'get_custom_templates');
    }

    // Запуск всех хуков
    public function run()
    {
        $this->loader->run();
    }

    // Получает название плагина
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    // Получает класс загрузчика
    public function get_loader()
    {
        return $this->loader;
    }

    // Получает версию плагина
    public function get_version()
    {
        return $this->version;
    }
}
