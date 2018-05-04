<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://swapnil.blog/
 * @since      1.0.0
 *
 * @package    Kalories
 * @subpackage Kalories/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Kalories
 * @subpackage Kalories/public
 * @author     Swapnil Patil <patilswapnilv@gmail.com>
 */
class Kalories_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Kalories_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kalories_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/kalories-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Kalories_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kalories_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/kalories-public.js', array('jquery'), $this->version, false);
	}

	/**
	 * Adds a default single view template for a kalories-cal post-type
	 *
	 * @param 	string 		$template 		The name of the template
	 * @return 	mixed 						The single template
	 */
	public function single_kalorie_template($template)
	{
		global $post;

		$return = $template;

		if ($post->post_type == 'kalories-cal') {
			$return = kalories_get_template('single-kalories-cal');
		}

		return $return;
	} // single_kalorie_template()


	public function archive_kalorie_template($template)
	{
		$return = $template;
		if (is_post_type_archive('kalories-cal')) {
			$return = kalories_get_template('archive-kalories-cal');
		}
		return $return;
	}

	/**
	 * Sets the class variable $options
	 */
	private function set_options()
	{
		$this->options = get_option($this->plugin_name . '-options');
	} // set_options()
}
