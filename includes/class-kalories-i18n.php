<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://swapnil.blog/
 * @since      1.0.0
 *
 * @package    Kalories
 * @subpackage Kalories/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Kalories
 * @subpackage Kalories/includes
 * @author     Swapnil Patil <patilswapnilv@gmail.com>
 */
class Kalories_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'kalories',
			false,
			dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
		);

	}



}
