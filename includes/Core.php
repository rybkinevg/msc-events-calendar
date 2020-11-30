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
        // Специальный класс загрузчик
        require_once(MSCEC_DIR . 'includes/Loader.php');

        $this->loader = new MSCEC_Loader();

        // Административная часть
        require_once(MSCEC_DIR . 'admin/Admin.php');

        // Публичная часть
        require_once(MSCEC_DIR . 'public/Public.php');
    }

    // Регистрация хуков административной части
    private function define_admin_hooks()
    {
        $plugin_admin = new MSCEC_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
    }

    // Регистрация хуков публичной части
    private function define_public_hooks()
    {
        $plugin_public = new MSCEC_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
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
