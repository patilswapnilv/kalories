<?php

/**
 * Fired during plugin activation
 *
 * @link       https://swapnil.blog/
 * @since      1.0.0
 *
 * @package    Kalories
 * @subpackage Kalories/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Kalories
 * @subpackage Kalories/includes
 * @author     Swapnil Patil <patilswapnilv@gmail.com>
 */
class Kalories_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-kalories-admin.php';

		Kalories_Admin::new_cpt_meal();
		Kalories_Admin::new_cpt_kalories_cal();
		//Kalories_Admin::new_taxonomy_type();

		flush_rewrite_rules();
		//
		$opts = array();
		$options = Kalories_Admin::get_options_list();

		foreach ($options as $option) {

			$opts[$option[0]] = $option[2];

		}

		update_option('kalories-options', $opts);

		Kalories_Admin::add_admin_notices();

	} // activate()
	} // class
