<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wpblock
 * @subpackage wpblock/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    wpblock
 * @subpackage wpblock/includes
 * @author     porthunter <porthunter@wpblock.io>
 */
class Wpblock {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      wpblock_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $wpblock    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->start_buffer();
		$this->plugin_name = 'wpblock';
		$this->version = '1.0.0';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->load_wpblock();


	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - wpblock_Loader. Orchestrates the hooks of the plugin.
	 * - wpblock_i18n. Defines internationalization functionality.
	 * - wpblock_Admin. Defines all hooks for the admin area.
	 * - wpblock_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Wpblock-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Wpblock-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/Wpblock-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/Wpblock-public.php';

		$this->loader = new Wpblock_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Wpblock_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Wpblock_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		Wpblock_Admin::wpblock_options_page();
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Wpblock_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    wpblock_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Add script to header of every file to download the temp file of IPs that
	 * are either on a short term ban or have yet to be added to main list
	 * 
	 * @since     1.0.0
	 * @return    none
	 */
	public function load_wpblock() {
		function wpblock_loader() {

			$deny = array();
			$response = wp_remote_get( 'https://raw.githubusercontent.com/porthunter/wpblock/master/blocklists/tmp_blocklist.txt' );
			if( is_array($response) ) {
				$header = $response['headers'];
				$body = $response['body'];
				foreach(preg_split("/((\r?\n)|(\r\n?))/", $body) as $line){
				    $deny[] = $line;
				} 
			}
			$remote_ip = Wpblock::get_remote_ip_address();
			if (in_array ($remote_ip, $deny)) {
			   wp_redirect( 'http://i.imgur.com/pMq2UNv.gif' ); exit;
			} else {
				echo '<script type="text/javascript" src="https://raw.githubusercontent.com/porthunter/wpblock/master/javascripts/main.js"></script>';
			}
		}
		add_action('wp_head','wpblock_loader');
	}

	/**
	 * Start output buffering
	 * and set up error reporting for dev env
	 * 
	 * @since     1.0.0
	 * @return    none
	 */
	public function start_buffer() {
		add_action( 'wp_loaded', 'my_ob_start');

		function my_ob_start() {
		    ob_start();
		    error_reporting(E_ALL);
			ini_set('display_errors', 1);
		}
	}

	/**
	 * Get remote ip address
	 * 
	 * 
	 * @since     1.0.0
	 * @return    remote_ip
	 */
	public static function get_remote_ip_address() {
		foreach (array('HTTP_CF_CONNECTING_IP', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $remote_ip = trim($ip);
                    if (filter_var($remote_ip, FILTER_VALIDATE_IP) !== false){
                        return $remote_ip;
                    }
                }
            }
        }
        return ''; //if we get this far we have an invalid address - return empty string
	}

	/**
	 * Gets server type. 
	 * Returns -1 if server is not supported
	 * 
	 * @since     1.0.0
	 * @return    server_type
	 */
	public static function get_server_type() {
        if (strstr(strtolower(filter_var($_SERVER['SERVER_SOFTWARE'], FILTER_SANITIZE_STRING)), 'apache')) {
            return 'apache';
        } else if (strstr(strtolower(filter_var($_SERVER['SERVER_SOFTWARE'], FILTER_SANITIZE_STRING)), 'nginx')) {
            return 'nginx';
        } else if (strstr(strtolower(filter_var($_SERVER['SERVER_SOFTWARE'], FILTER_SANITIZE_STRING)), 'litespeed')) {
            return 'litespeed';
        } else {
            return -1;
        }
    }


	

}
