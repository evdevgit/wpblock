<?php

/**
 *
 * @link              http://wpblock.io
 * @since             1.0.0
 * @package           wpblock
 *
 * @wordpress-plugin
 * Plugin Name:       wpblock
 * Plugin URI:        http://wpblock.io
 * Description:       Secure your website with our extensive database of malicious IP's
 * Version:           1.0.0
 * Author:            Evsec
 * Author URI:        http://evsec.eu
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpblock
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/porthunter/wpblock
 * GitHub Branch:     master
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

include_once('wpblock-core.php');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/Wpblock-activator.php
 */
function activate_wpblock() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/Wpblock-activator.php';
	Wpblock_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/Wpblock-deactivator.php
 */
function deactivate_wpblock() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/Wpblock-deactivator.php';
	Wpblock_Deactivator::deactivate();
}

/**
 * The code runs after theme is set up and sets up cron
 * To download blocklist
 * 
 * @since    1.0.0
 */
function setup_cron() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/Wpblock-cron.php';
	Wpblock_Cron::configure_cron();
}

add_action( 'init', 'setup_cron' );
register_activation_hook( __FILE__, 'activate_wpblock' );
register_deactivation_hook( __FILE__, 'deactivate_wpblock' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/Wpblock.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function wpblock() {
	$plugin = new Wpblock();
	$plugin->run();
}
wpblock();
