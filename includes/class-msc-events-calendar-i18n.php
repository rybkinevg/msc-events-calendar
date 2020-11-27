<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/rybkinevg
 * @since      1.0.0
 *
 * @package    Msc_Events_Calendar
 * @subpackage Msc_Events_Calendar/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Msc_Events_Calendar
 * @subpackage Msc_Events_Calendar/includes
 * @author     Evgeniy Rybkin <rybkin@msc.moscow>
 */
class Msc_Events_Calendar_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'msc-events-calendar',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
