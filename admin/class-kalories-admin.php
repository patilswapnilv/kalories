<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://swapnil.blog/
 * @since      1.0.0
 *
 * @package    Kalories
 * @subpackage Kalories/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Kalories
 * @subpackage Kalories/admin
 * @author     Swapnil Patil <patilswapnilv@gmail.com>
 */
class Kalories_Admin
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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->set_options();
	}

	/**
	 * Adds notices for the admin to display.
	 * Saves them in a temporary plugin option.
	 * This method is called on plugin activation, so its needs to be static.
	 */
	public static function add_admin_notices()
	{
		$notices = get_option('kalories_deferred_admin_notices', array());
		//$notices[] 	= array( 'class' => 'updated', 'notice' => esc_html__( 'Kalories: Custom Activation Message', 'kalories' ) );
		//$notices[] 	= array( 'class' => 'error', 'notice' => esc_html__( 'Kalories: Problem Activation Message', 'kalories' ) );

		apply_filters('kalories_admin_notices', $notices);
		update_option('kalories_deferred_admin_notices', $notices);
	} // add_admin_notices

	/**
	 * Adds a settings page link to a menu
	 *
	 * @link 		https://codex.wordpress.org/Administration_Menus
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function add_menu()
	{

							// Top-level page
		// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

		// Submenu Page
		// add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);

		add_submenu_page(
							  'edit.php?post_type=meal',
							  apply_filters($this->plugin_name . '-settings-page-title', esc_html__('Kalories Settings', 'kalories')),
							  apply_filters($this->plugin_name . '-settings-menu-title', esc_html__('Settings', 'kalories')),
							  'manage_options',
							  $this->plugin_name . '-settings',
							  array($this, 'page_options')
							);

		add_submenu_page(
							  'edit.php?post_type=meal',
							  apply_filters($this->plugin_name . '-settings-page-title', esc_html__('Kalories Help', 'kalories')),
							  apply_filters($this->plugin_name . '-settings-menu-title', esc_html__('Help', 'kalories')),
							  'manage_options',
							  $this->plugin_name . '-help',
							  array($this, 'page_help')
							);
	} // add_menu()

	/**
	 * Manages any updates or upgrades needed before displaying notices.
	 * Checks plugin version against version required for displaying
	 * notices.
	 */
	public function admin_notices_init()
	{
		$current_version = '1.0.0';

		if ($this->version !== $current_version) {

							  // Do whatever upgrades needed here.

			update_option('my_plugin_version', $current_version);

			$this->add_notice();
		}
	} // admin_notices_init()

	/**
	 * Displays admin notices
	 *
	 * @return 	string 			Admin notices
	 */
	public function display_admin_notices()
	{
		$notices = get_option('kalories_deferred_admin_notices');

		if (empty($notices)) {
			return;
		}

		foreach ($notices as $notice) {
			echo '<div class="' . esc_attr($notice['class']) . '"><p>' . $notice['notice'] . '</p></div>';
		}

		delete_option('kalories_deferred_admin_notices');
	} // display_admin_notices()

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since 		1.0.0
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/kalories-admin.css', array(), $this->version, 'all');
	} // enqueue_styles()

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since 		1.0.0
	 */
	public function enqueue_scripts($hook_suffix)
	{
		global $post_type;

		$screen = get_current_screen();

		if ('meal' === $post_type || $screen->id === $hook_suffix) {
			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/' . $this->plugin_name . '-file-uploader.min.js', array('jquery'), $this->version, true);
			wp_enqueue_script($this->plugin_name . '-repeater', plugin_dir_url(__FILE__) . 'js/' . $this->plugin_name . '-repeater.min.js', array('jquery'), $this->version, true);
			wp_enqueue_script('jquery-ui-datepicker');

			$localize['repeatertitle'] = __('File Name', 'kalories');

			wp_localize_script('kalories', 'nhdata', $localize);
		}
	} // enqueue_scripts()

	/**
	 * Creates a checkbox field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_checkbox($args)
	{
		$defaults['class'] 			= '';
		$defaults['description'] = '';
		$defaults['label'] 			= '';
		$defaults['name'] = $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['value'] 			= 0;

		apply_filters($this->plugin_name . '-field-checkbox-options-defaults', $defaults);

		$atts = wp_parse_args($args, $defaults);

		if (!empty($this->options[$atts['id']])) {
			$atts['value'] = $this->options[$atts['id']];
		}

		include(plugin_dir_path(__FILE__) . 'partials/' . $this->plugin_name . '-admin-field-checkbox.php');
	} // field_checkbox()

	/**
	 * Creates an editor field
	 *
	 * NOTE: ID must only be lowercase letter, no spaces, dashes, or underscores.
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_editor($args)
	{
		$defaults['description'] = '';
		$defaults['settings'] = array('textarea_name' => $this->plugin_name . '-options[' . $args['id'] . ']');
		$defaults['value'] = '';

		apply_filters($this->plugin_name . '-field-editor-options-defaults', $defaults);

		$atts = wp_parse_args($args, $defaults);

		if (!empty($this->options[$atts['id']])) {
			$atts['value'] = $this->options[$atts['id']];
		}

		include(plugin_dir_path(__FILE__) . 'partials/' . $this->plugin_name . '-admin-field-editor.php');
	} // field_editor()

	/**
	 * Creates a set of radios field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_radios($args)
	{
		$defaults['class'] 			= '';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['value'] 			= 0;

		apply_filters($this->plugin_name . '-field-radios-options-defaults', $defaults);

		$atts = wp_parse_args($args, $defaults);

		if (! empty($this->options[$atts['id']])) {
			$atts['value'] = $this->options[$atts['id']];
		}

		include(plugin_dir_path(__FILE__) . 'partials/' . $this->plugin_name . '-admin-field-radios.php');
	} // field_radios()

	public function field_repeater($args)
	{
		$defaults['class'] 			= 'repeater';
		$defaults['fields'] 		= array();
		$defaults['id'] 			= '';
		$defaults['label-add'] 		= 'Add Item';
		$defaults['label-edit'] 	= 'Edit Item';
		$defaults['label-header'] 	= 'Item Name';
		$defaults['label-remove'] 	= 'Remove Item';
		$defaults['title-field'] 	= '';



		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';


		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';


		apply_filters($this->plugin_name . '-field-repeater-options-defaults', $defaults);

		$setatts 	= wp_parse_args($args, $defaults);
		$count 		= 1;
		$repeater 	= array();

		if (! empty($this->options[$setatts['id']])) {
			$repeater = maybe_unserialize($this->options[$setatts['id']][0]);
		}

		if (! empty($repeater)) {
			$count = count($repeater);
		}

		include(plugin_dir_path(__FILE__) . 'partials/' . $this->plugin_name . '-admin-field-repeater.php');
	} // field_repeater()

	/**
	 * Creates a select field
	 *
	 * Note: label is blank since its created in the Settings API
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_select($args)
	{
		$defaults['aria'] = '';
		$defaults['blank'] 			= '';
		$defaults['class'] 			= 'widefat';
		$defaults['context'] = '';
		$defaults['description'] = '';
		$defaults['label'] 			= '';
		$defaults['name'] = $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['selections'] = array();
		$defaults['value'] 			= '';

		apply_filters($this->plugin_name . '-field-select-options-defaults', $defaults);

		$atts = wp_parse_args($args, $defaults);

		if (!empty($this->options[$atts['id']])) {
			$atts['value'] = $this->options[$atts['id']];
		}

		if (empty($atts['aria']) && !empty($atts['description'])) {
			$atts['aria'] = $atts['description'];
		} elseif (empty($atts['aria']) && !empty($atts['label'])) {
			$atts['aria'] = $atts['label'];
		}

		include(plugin_dir_path(__FILE__) . 'partials/' . $this->plugin_name . '-admin-field-select.php');
	} // field_select()

	/**
	 * Creates a text field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_text($args)
	{
		$defaults['class'] 			= 'text widefat';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['placeholder'] 	= '';
		$defaults['type'] 			= 'text';
		$defaults['value'] = '';

		apply_filters($this->plugin_name . '-field-text-options-defaults', $defaults);

		$atts = wp_parse_args($args, $defaults);

		if (!empty($this->options[$atts['id']])) {
			$atts['value'] = $this->options[$atts['id']];
		}

		include(plugin_dir_path(__FILE__) . 'partials/' . $this->plugin_name . '-admin-field-text.php');
	} // field_text()

	/**
	 * Creates a textarea field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_textarea($args)
	{
		$defaults['class'] = 'large-text';
		$defaults['cols'] = 50;
		$defaults['context'] = '';
		$defaults['description'] = '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['rows'] 			= 10;
		$defaults['value'] 			= '';

		apply_filters($this->plugin_name . '-field-textarea-options-defaults', $defaults);

		$atts = wp_parse_args($args, $defaults);

		if (!empty($this->options[$atts['id']])) {
			$atts['value'] = $this->options[$atts['id']];
		}

		include(plugin_dir_path(__FILE__) . 'partials/' . $this->plugin_name . '-admin-field-textarea.php');
	} // field_textarea()

	/**
	 * Returns an array of options names, fields types, and default values
	 *
	 * @return 		array 			An array of options
	 */
	public static function get_options_list()
	{
		$options = array();

		$options[] = array('message-no-openings', 'text', 'Thank you for your interest! There are no job openings at this time.');
		$options[] = array('howtoapply', 'editor', '');
		$options[] = array('repeat-test', 'repeater', array(array('test1', 'text'), array('test2', 'text'), array('test3', 'text')));

		return $options;
	} // get_options_list()

	/**
	 * Creates a new custom post type
	 *
	 * @since     1.0.0
	 * @access     public
	 * @uses     register_post_type()
	 */
	public static function new_cpt_meal()
	{
		$cap_type = 'post';
		$plural   = 'Meals';
		$single   = 'Meal';
		$cpt_name = 'meal';

		$opts['can_export']           = true;
		$opts['capability_type']      = $cap_type;
		$opts['description']          = '';
		$opts['exclude_from_search']  = false;
		$opts['has_archive']          = true;
		$opts['hierarchical']         = false;
		$opts['map_meta_cap']         = true;
		$opts['menu_icon']            = 'dashicons-carrot';
		$opts['menu_position']        = 5;
		$opts['public']               = true;
		$opts['publicly_querable']    = true;
		$opts['query_var']            = true;
		$opts['register_meta_box_cb'] = '';
		$opts['rewrite']              = false;
		$opts['show_in_admin_bar']    = true;
		$opts['show_in_menu']         = true;
		$opts['show_in_nav_menu']     = true;
		$opts['show_ui']              = true;
		$opts['supports']             = array(
														   'title',
														  'editor',
														  'thumbnail'
										  );
		$opts['taxonomies'] = array( );

		$opts['capabilities']['delete_others_posts']    = "delete_others_{$cap_type}s";
		$opts['capabilities']['delete_post']            = "delete_{$cap_type}";
		$opts['capabilities']['delete_posts']           = "delete_{$cap_type}s";
		$opts['capabilities']['delete_private_posts']   = "delete_private_{$cap_type}s";
		$opts['capabilities']['delete_published_posts'] = "delete_published_{$cap_type}s";
		$opts['capabilities']['edit_others_posts']      = "edit_others_{$cap_type}s";
		$opts['capabilities']['edit_post']              = "edit_{$cap_type}";
		$opts['capabilities']['edit_posts']             = "edit_{$cap_type}s";
		$opts['capabilities']['edit_private_posts']     = "edit_private_{$cap_type}s";
		$opts['capabilities']['edit_published_posts']   = "edit_published_{$cap_type}s";
		$opts['capabilities']['publish_posts']          = "publish_{$cap_type}s";
		$opts['capabilities']['read_post']              = "read_{$cap_type}";
		$opts['capabilities']['read_private_posts']     = "read_private_{$cap_type}s";

		$opts['labels']['add_new']            = esc_html__("Add New {$single}", 'kalories');
		$opts['labels']['add_new_item']       = esc_html__("Add New {$single}", 'kalories');
		$opts['labels']['all_items']          = esc_html__($plural, 'kalories');
		$opts['labels']['edit_item']          = esc_html__("Edit {$single}", 'kalories');
		$opts['labels']['menu_name']          = esc_html__($plural, 'kalories');
		$opts['labels']['name']               = esc_html__($plural, 'kalories');
		$opts['labels']['name_admin_bar']     = esc_html__($single, 'kalories');
		$opts['labels']['new_item']           = esc_html__("New {$single}", 'kalories');
		$opts['labels']['not_found']          = esc_html__("No {$plural} Found", 'kalories');
		$opts['labels']['not_found_in_trash'] = esc_html__("No {$plural} Found in Trash", 'kalories');
		$opts['labels']['parent_item_colon']  = esc_html__("Parent {$plural} :", 'kalories');
		$opts['labels']['search_items']       = esc_html__("Search {$plural}", 'kalories');
		$opts['labels']['singular_name']      = esc_html__($single, 'kalories');
		$opts['labels']['view_item']          = esc_html__("View {$single}", 'kalories');

		$opts['rewrite']['ep_mask']    = EP_PERMALINK;
		$opts['rewrite']['feeds']      = false;
		$opts['rewrite']['pages']      = true;
		$opts['rewrite']['slug']       = esc_html__(strtolower($plural), 'kalories');
		$opts['rewrite']['with_front'] = false;

		$opts = apply_filters('kalories-cpt-options', $opts);

		register_post_type(strtolower($cpt_name), $opts);
	} // new_cpt_meal)

	/**
	 * Creates a new custom post type
	 *
	 * @since     1.0.0
	 * @access     public
	 * @uses     register_post_type()
	 */
	public static function new_cpt_kalories_cal()
	{
		$cap_type = 'post';
		$plural   = 'Kalories';
		$single   = 'Kalorie';
		$cpt_name = 'kalories-cal';

		$opts[ 'can_export' ]           = true;
		$opts[ 'capability_type' ]      = $cap_type;
		$opts[ 'description' ]          = '';
		$opts[ 'exclude_from_search' ]  = false;
		$opts[ 'has_archive' ]          = true;
		$opts[ 'hierarchical' ]         = false;
		$opts[ 'map_meta_cap' ]         = true;
		$opts[ 'menu_icon' ]            = 'dashicons-carrot';
		$opts[ 'menu_position' ]        = 6;
		$opts[ 'public' ]               = true;
		$opts[ 'publicly_querable' ]    = true;
		$opts[ 'query_var' ]            = true;
		$opts[ 'register_meta_box_cb' ] = '';
		$opts[ 'rewrite' ]              = false;
		$opts[ 'show_in_admin_bar' ]    = true;
		$opts[ 'show_in_menu' ]         = true;
		$opts[ 'show_in_nav_menu' ]     = true;
		$opts[ 'show_ui' ]              = true;
		$opts['supports']             = array(
																   'title',
																  'editor',
																  'thumbnail'
												  );
		$opts['taxonomies'] = array( );

		$opts['capabilities']['delete_others_posts']    = "delete_others_{$cap_type}s";
		$opts['capabilities']['delete_post']            = "delete_{$cap_type}";
		$opts['capabilities']['delete_posts']           = "delete_{$cap_type}s";
		$opts['capabilities']['delete_private_posts']   = "delete_private_{$cap_type}s";
		$opts['capabilities']['delete_published_posts'] = "delete_published_{$cap_type}s";
		$opts['capabilities']['edit_others_posts']      = "edit_others_{$cap_type}s";
		$opts['capabilities']['edit_post']              = "edit_{$cap_type}";
		$opts['capabilities']['edit_posts']             = "edit_{$cap_type}s";
		$opts['capabilities']['edit_private_posts']     = "edit_private_{$cap_type}s";
		$opts['capabilities']['edit_published_posts']   = "edit_published_{$cap_type}s";
		$opts['capabilities']['publish_posts']          = "publish_{$cap_type}s";
		$opts['capabilities']['read_post']              = "read_{$cap_type}";
		$opts['capabilities']['read_private_posts']     = "read_private_{$cap_type}s";

		$opts['labels']['add_new']            = esc_html__("Add New {$single}", 'kalories');
		$opts['labels']['add_new_item']       = esc_html__("Add New {$single}", 'kalories');
		$opts['labels']['all_items']          = esc_html__($plural, 'kalories');
		$opts['labels']['edit_item']          = esc_html__("Edit {$single}", 'kalories');
		$opts['labels']['menu_name']          = esc_html__($plural, 'kalories');
		$opts['labels']['name']               = esc_html__($plural, 'kalories');
		$opts['labels']['name_admin_bar']     = esc_html__($single, 'kalories');
		$opts['labels']['new_item']           = esc_html__("New {$single}", 'kalories');
		$opts['labels']['not_found']          = esc_html__("No {$plural} Found", 'kalories');
		$opts['labels']['not_found_in_trash'] = esc_html__("No {$plural} Found in Trash", 'kalories');
		$opts['labels']['parent_item_colon']  = esc_html__("Parent {$plural} :", 'kalories');
		$opts['labels']['search_items']       = esc_html__("Search {$plural}", 'kalories');
		$opts['labels']['singular_name']      = esc_html__($single, 'kalories');
		$opts['labels']['view_item']          = esc_html__("View {$single}", 'kalories');

		$opts['rewrite']['ep_mask']    = EP_PERMALINK;
		$opts['rewrite']['feeds']      = false;
		$opts['rewrite']['pages']      = true;
		$opts['rewrite']['slug']       = esc_html__(strtolower($plural), 'kalories');
		$opts['rewrite']['with_front'] = false;

		$opts = apply_filters('kalories-cpt-options', $opts);

		register_post_type(strtolower($cpt_name), $opts);
	} // new_cpt_kalories()

	//acf fields from local
	public function acf_fields()
	{
		if (function_exists('acf_add_local_field_group')):

	  acf_add_local_field_group(array(
		  'key' => 'group_5adc22081425d',
		  'title' => 'Kalories Calculation',
		  'fields' => array(
			  array(
				  'key' => 'field_5ad9c05d1af5d',
				  'label' => 'Select your meal',
				  'name' => 'select_your_meal',
				  'type' => 'relationship',
				  'instructions' => 'Select your meal',
				  'required' => 1,
				  'conditional_logic' => 0,
				  'wrapper' => array(
					  'width' => '',
					  'class' => '',
					  'id' => '',
				  ),
				  'return_format' => 'id',
				  'post_type' => array(
					  0 => 'meal',
				  ),
				  'taxonomy' => array(
				  ),
				  'filters' => array(
					  0 => 'search',
				  ),
				  'max' => '',
				  'min' => 0,
				  'elements' => array(
					  0 => 'post_type',
				  ),
			  ),
			  array(
				  'key' => 'field_5ad9c11af80ce',
				  'label' => 'Calories',
				  'name' => 'calories',
				  'type' => 'post_object',
				  'instructions' => '',
				  'required' => 0,
				  'conditional_logic' => 0,
				  'wrapper' => array(
					  'width' => '',
					  'class' => '',
					  'id' => '',
				  ),
				  'post_type' => array(
					  0 => 'meal',
				  ),
				  'taxonomy' => array(
				  ),
				  'allow_null' => 0,
				  'multiple' => 0,
				  'return_format' => 'object',
				  'ui' => 1,
			  ),
			  array(
				  'key' => 'field_5ad9c189f80cf',
				  'label' => 'Date',
				  'name' => 'date',
				  'type' => 'date_picker',
				  'instructions' => 'Pick date',
				  'required' => 0,
				  'conditional_logic' => 0,
				  'wrapper' => array(
					  'width' => '',
					  'class' => '',
					  'id' => '',
				  ),
				  'first_day' => 1,
				  'return_format' => 'd/m/Y',
				  'display_format' => 'd/m/Y',
			  ),
			  array(
				  'key' => 'field_5ad9c1c3f80d0',
				  'label' => 'time',
				  'name' => 'time',
				  'type' => 'text',
				  'instructions' => '',
				  'required' => 0,
				  'conditional_logic' => 0,
				  'wrapper' => array(
					  'width' => '',
					  'class' => '',
					  'id' => '',
				  ),
				  'default_value' => '',
				  'placeholder' => '',
				  'prepend' => '',
				  'append' => '',
				  'formatting' => 'html',
				  'maxlength' => '',
			  ),
		  ),
		  'location' => array(
			  array(
				  array(
					  'param' => 'post_type',
					  'operator' => '==',
					  'value' => 'kalories-cal',
				  ),
			  ),
		  ),
		  'menu_order' => 0,
		  'position' => 'acf_after_title',
		  'style' => 'seamless',
		  'label_placement' => 'top',
		  'instruction_placement' => 'label',
		  'hide_on_screen' => array(
			  0 => 'permalink',
			  1 => 'the_content',
			  2 => 'excerpt',
			  3 => 'custom_fields',
			  4 => 'discussion',
			  5 => 'comments',
			  6 => 'author',
			  7 => 'format',
			  8 => 'featured_image',
			  9 => 'send-trackbacks',
		  ),
		  'active' => 1,
		  'description' => '',
	  ));

		acf_add_local_field_group(array(
		  'key' => 'group_5adc22081bfca',
		  'title' => 'Meal data',
		  'fields' => array(
			  array(
				  'key' => 'field_5ad9bfd28b2bf',
				  'label' => 'How many calories?',
				  'name' => 'how_many_calories',
				  'type' => 'number',
				  'instructions' => 'Add how many calories this meal is...',
				  'required' => 1,
				  'conditional_logic' => 0,
				  'wrapper' => array(
					  'width' => '',
					  'class' => '',
					  'id' => '',
				  ),
				  'default_value' => '',
				  'placeholder' => '',
				  'prepend' => '',
				  'append' => '',
				  'min' => 1,
				  'max' => '',
				  'step' => '',
			  ),
		  ),
		  'location' => array(
			  array(
				  array(
					  'param' => 'post_type',
					  'operator' => '==',
					  'value' => 'meal',
				  ),
			  ),
		  ),
		  'menu_order' => 0,
		  'position' => 'normal',
		  'style' => 'seamless',
		  'label_placement' => 'top',
		  'instruction_placement' => 'label',
		  'hide_on_screen' => array(
		  ),
		  'active' => 1,
		  'description' => '',
	  ));

		endif;
	}

	/**
	 * Creates the help page
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function page_help()
	{
		include(plugin_dir_path(__FILE__) . 'partials/kalories-admin-page-help.php');
	} // page_help()

	/**
	 * Creates the options page
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function page_options()
	{
		include(plugin_dir_path(__FILE__) . 'partials/kalories-admin-page-settings.php');
	} // page_options()

	/**
	 * Registers settings fields with WordPress
	 */
	public function register_fields()
	{

							// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );

		// add_settings_field(
		//   'message-no-openings',
		//   apply_filters( $this->plugin_name . 'label-message-no-openings', esc_html__( 'No Meals Message', 'kalories' ) ),
		//   array( $this, 'field_text' ),
		//   $this->plugin_name,
		//   $this->plugin_name . '-messages',
		//   array(
		//     'description' 	=> 'This message displays on the page if no meal posts are found.',
		//     'id' 			=> 'message-no-meals',
		//     'value' 		=> 'Thank you for your interest! There are no meals at this time.',
		//   )
		// );
		//
		// add_settings_field(
		//   'how-to-apply',
		//   apply_filters( $this->plugin_name . 'label-how-to-apply', esc_html__( 'How to Apply', 'kalories' ) ),
		//   array( $this, 'field_editor' ),
		//   $this->plugin_name,
		//   $this->plugin_name . '-messages',
		//   array(
		//     'description' 	=> 'Instructions for applying (contact email, phone, fax, address, etc).',
		//     'id' 			=> 'howtoapply'
		//   )
		// );

		add_settings_field(
							  'repeater-test',
							  apply_filters($this->plugin_name . 'label-repeater-test', esc_html__('Repeater Test', 'kalories')),
							  array($this, 'field_repeater'),
							  $this->plugin_name,
							  $this->plugin_name . '-messages',
							  array(
								'description' 	=> 'Instructions for applying (contact email, phone, fax, address, etc).',
								'fields' 		=> array(
								  array(
									'text' => array(
									  'class' 		=> '',
									  'description' 	=> '',
									  'id' 			=> 'test1',
									  'label' 		=> '',
									  'name' 			=> $this->plugin_name . '-options[test1]',
									  'placeholder' 	=> 'Test 1',
									  'type' 			=> 'text',
									  'value' 		=> ''
									),
								  ),
								  array(
									'text' => array(
									  'class' 		=> '',
									  'description' 	=> '',
									  'id' 			=> 'test2',
									  'label' 		=> '',
									  'name' 			=> $this->plugin_name . '-options[test2]',
									  'placeholder' 	=> 'Test 2',
									  'type' 			=> 'text',
									  'value' 		=> ''
									),
								  ),
								  array(
									'text' => array(
									  'class' 		=> '',
									  'description' 	=> '',
									  'id' 			=> 'test3',
									  'label' 		=> '',
									  'name' 			=> $this->plugin_name . '-options[test3]',
									  'placeholder' 	=> 'Test 3',
									  'type' 			=> 'text',
									  'value' 		=> ''
									),
								  ),
								),
								'id' 			=> 'repeater-test',
								'label-add' 	=> 'Add Test',
								'label-edit' 	=> 'Edit Test',
								'label-header' 	=> 'TEST',
								'label-remove' 	=> 'Remove Test',
								'title-field' 	=> 'test1'

							  )
							);
	} // register_fields()

	/**
	 * Registers settings sections with WordPress
	 */
	public function register_sections()
	{

							// add_settings_section( $id, $title, $callback, $menu_slug );

		add_settings_section(
							  $this->plugin_name . '-messages',
							  apply_filters($this->plugin_name . 'section-title-messages', esc_html__('Settings', 'kalories')),
							  array($this, 'section_messages'),
							  $this->plugin_name
							);
	} // register_sections()

	/**
	 * Registers plugin settings
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function register_settings()
	{

							// register_setting( $option_group, $option_name, $sanitize_callback );

		register_setting(
							  $this->plugin_name . '-options',
							  $this->plugin_name . '-options',
							  array($this, 'validate_options')
							);
	} // register_settings()

	private function sanitizer($type, $data)
	{
		if (empty($type)) {
			return;
		}
		if (empty($data)) {
			return;
		}

		$return = '';
		$sanitizer = new Kalories_Sanitize();

		$sanitizer->set_data($data);
		$sanitizer->set_type($type);

		$return = $sanitizer->clean();

		unset($sanitizer);

		return $return;
	} // sanitizer()

	/**
	 * Creates a settings section
	 *
	 * @since 		1.0.0
	 * @param 		array 		$params 		Array of parameters for the section
	 * @return 		mixed 						The settings section
	 */
	public function section_messages($params)
	{
		include(plugin_dir_path(__FILE__) . 'partials/kalories-admin-section-messages.php');
	} // section_messages()

	/**
	 * Sets the class variable $options
	 */
	private function set_options()
	{
		$this->options = get_option($this->plugin_name . '-options');
	} // set_options()

	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_options_page()
	{
		$this->plugin_screen_hook_suffix = add_options_page(__('Kalories Settings', 'kalories'), __('Kalories', 'kalories'), 'manage_options', $this->plugin_name, array(
												 $this,
												'display_options_page'
								));
	}

	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_options_page()
	{
		include_once 'partials/kalories-admin-display.php';
	}


	/**
	 * Validates saved options
	 *
	 * @since 		1.0.0
	 * @param 		array 		$input 			array of submitted plugin options
	 * @return 		array 						array of validated plugin options
	 */
	public function validate_options($input)
	{

		 //wp_die( print_r( $input ) );

		$valid = array();
		$options = $this->get_options_list();

		foreach ($options as $option) {
			$name = $option[0];
			$type = $option[1];

			if ('repeater' === $type && is_array($option[2])) {
				$clean = array();

				foreach ($option[2] as $field) {
					foreach ($input[$field[0]] as $data) {
						if (empty($data)) {
							continue;
						}

						$clean[$field[0]][] = $this->sanitizer($field[1], $data);
					} // foreach
				} // foreach

				$count = kalories_get_max($clean);

				for ($i = 0; $i < $count; $i++) {
					foreach ($clean as $field_name => $field) {
						$valid[$option[0]][$i][$field_name] = $field[$i];
					} // foreach $clean
				} // for
			} else {
				$valid[$option[0]] = $this->sanitizer($type, $input[$name]);
			}

			/*if ( ! isset( $input[$option[0]] ) ) { continue; }

            $sanitizer = new Kalories_Sanitize();

            $sanitizer->set_data( $input[$option[0]] );
            $sanitizer->set_type( $option[1] );

            $valid[$option[0]] = $sanitizer->clean();

            if ( $valid[$option[0]] != $input[$option[0]] ) {

              add_settings_error( $option[0], $option[0] . '_error', esc_html__( $option[0] . ' error.', 'kalories' ), 'error' );

            }

            unset( $sanitizer );*/
		}

		return $valid;
	} // validate_options()
}
