<?php

/**
 * Plugin Name:       MSC Events Calendar
 * Plugin URI:        https://github.com/rybkinevg/msc-events-calendar
 * Description:       Календарь мероприятий для портала "Мой семейный центр"
 * Version:           2.0.2
 * Author:            Евгений Рыбкин
 * Author URI:        https://github.com/rybkinevg
 * Text Domain:       msc-events-calendar
 * GitHub Plugin URI: https://github.com/rybkinevg/msc-events-calendar
 */

// Если вызов файла был напрямую, то заблокировать
if (!defined('ABSPATH')) {
	die;
}

# Константы

// Актуальная версия плагина
define('MSCEC_VERSION', '2.0.2');

// Абсолютный путь до плагина
define('MSCEC_DIR', plugin_dir_path(__FILE__));

// Ссылка до плагина
define('MSCEC_URL', plugin_dir_url(__FILE__));

# Активация и деактивация

function activate_msc_events_calendar()
{
	require_once(MSCEC_DIR . 'includes/Activator.php');
	MSCEC_Activator::activate();
}

function deactivate_msc_events_calendar()
{
	require_once(MSCEC_DIR . 'includes/Deactivator.php');
	MSCEC_Deactivator::deactivate();
}

function uninstall_msc_events_calendar()
{
	require_once(MSCEC_DIR . 'includes/Uninstall.php');
	MSCEC_Uninstall::uninstall();
}

register_activation_hook(__FILE__, 'activate_msc_events_calendar');
register_deactivation_hook(__FILE__, 'deactivate_msc_events_calendar');
register_uninstall_hook(__FILE__, 'uninstall_msc_events_calendar');

# Вызов плагина

// Подключение главного файла плагина
require(MSCEC_DIR . 'includes/Core.php');

function run_msc_events_calendar()
{
	$plugin = new MSCEC_Core();
	$plugin->run();
}

run_msc_events_calendar();
