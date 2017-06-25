<?php
/**
 * Plugin Name: Waste Art
 * Plugin URI:  http://pluginever.com
 * Description: The best WordPress plugin ever made!
 * Version:     0.1.0
 * Author:      PluginEver
 * Author URI:  http://pluginever.com
 * Donate link: http://pluginever.com
 * License:     GPLv2+
 * Text Domain: waste_art
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2017 PluginEver (email : support@pluginever.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * Main initiation class
 */

class Waste_Art {

	public $version = '1.0.0';

	public $dependency_plugins = [];

	
	/**
	 * Sets up our plugin
	 * @since  0.1.0
	 */
	public function __construct() {

		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		add_action( 'admin_init', array( $this, 'admin_hooks' ) );
		add_action( 'init', [ $this, 'localization_setup' ] );
		$this->define_constants();
		$this->includes();
		add_action('wp_enqueue_scripts', [$this, 'load_assets']);
	}

	/**
	 * Activate the plugin
	 */
	function activate() {
		// Make sure any rewrite functionality has been loaded
		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin
	 * Uninstall routines should be in uninstall.php
	 */
	function deactivate() {

	}

	/**
	 * Initialize plugin for localization
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function localization_setup() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'waste_art' );
		load_textdomain( 'waste_art', WP_LANG_DIR . '/waste_art/waste_art-' . $locale . '.mo' );
		load_plugin_textdomain( 'waste_art', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}



	/**
	 * Hooks for the Admin
	 * @since  0.1.0
	 * @return null
	 */
	public function admin_hooks() {

	}

	/**
	 * Include a file from the includes directory
	 * @since  0.1.0
	 * @param  string $filename Name of the file to be included
	 */
	public function includes( ) {
		require VWA_INCLUDES .'/functions.php';
	}


	/**
	 * Define Add-on constants
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function define_constants() {
		define( 'VWA_VERSION', $this->version );
		define( 'VWA_FILE', __FILE__ );
		define( 'VWA_PATH', dirname( VWA_FILE ) );
		define( 'VWA_INCLUDES', VWA_PATH . '/includes' );
		define( 'VWA_URL', plugins_url( '', VWA_FILE ) );
		define( 'VWA_ASSETS', VWA_URL . '/assets' );
		define( 'VWA_VIEWS', VWA_PATH . '/views' );
		define( 'VWA_TEMPLATES_DIR', VWA_PATH . '/templates' );
	}

	
	/**
	 * Add all the assets required by the plugin
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function load_assets(){
		wp_register_style('waste-art', VWA_ASSETS.'/css/waste-art.css', [], date('i'));
		wp_register_script('waste-art', VWA_ASSETS.'/js/waste-art.js', ['jquery'], date('i'), true);
		wp_localize_script('waste-art', 'jsobject', ['ajaxurl' => admin_url( 'admin-ajax.php' )]);
		wp_enqueue_style('waste-art');
		wp_enqueue_script('waste-art');
	}




}

// init our class
$GLOBALS['Waste_Art'] = new Waste_Art();

/**
 * Grab the $Waste_Art object and return it
 */
function waste_art() {
	global $Waste_Art;
	return $Waste_Art;
}
