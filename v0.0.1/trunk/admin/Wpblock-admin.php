<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wpblock.io
 * @since      1.0.0
 *
 * @package    wpblock
 * @subpackage wpblock/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    wpblock
 * @subpackage wpblock/admin
 * @author     Your Name <email@example.com>
 */
class Wpblock_Admin {

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
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpblock-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpblock-admin.js', array( 'jquery' ), $this->version, false );

	}
		 
		/**
		 * Build the options page
		 */
		public static function wpblock_options_page() {

			/**
			 * Add an admin submenu link under Settings
			 */
			function wpblock_add_options_menu_page() {
			     add_menu_page(
			          __( 'WP Block Settings', 'wpblock' ), // page title
			          __( 'WP Block', 'wpblock' ), // menu title
			          'manage_options',               // capability required to see the page
			          'wpblock_options',                // admin page slug, e.g. options-general.php?page=wporg_options
			          'wpblock_options_page'            // callback function to display the options page
			     );
			}
			add_action( 'admin_menu', 'wpblock_add_options_menu_page' );
			 
			/**
			 * Register the settings
			 */
			function wpblock_register_settings() {
			     register_setting(
			          'wpblock_options',  // settings section
			          'wpblock_hide_meta', // setting name
			          'wpblock_validate_input_callback'
			     );
			     register_setting(
			          'wpblock_options',  // settings section
			          'wpblock_user_htaccess' // setting name
			     );
			     register_setting(
			          'wpblock_options',  // settings section
			          'wpblock_blocklist' // setting name
			     );
			     register_setting(
			          'wpblock_permission_settings',  // settings section
			          'wpblock_permissions' // setting name
			     );

			    add_settings_section(
			        'section_settings_htaccess', // ID
			        'block-lists', // Title
			        'htaccess_settings', // print output
			        'wpblock_options' // menu slug
			    );
			}
			add_action( 'admin_init', 'wpblock_register_settings' );

			function htaccess_settings()
			{
				
			    _e( 'Please select from below the options you want to use...', 'wpblock' );
			    echo "<hr>";
			}
						 
			/**
			 * Build the options page
			 */
			function wpblock_options_page() {
			    if ( ! isset( $_REQUEST['settings-updated'] ) )
			        $_REQUEST['settings-updated'] = false; ?>
			 			 
			        <?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
			            <div class="updated fade"><p><strong><?php _e( 'WP Block options saved!', 'wpblock' ); ?></strong></p></div>
			        <?php endif; ?>
			           
			        <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
   
			        <form method="post" action="options.php">
			            <?php settings_fields( 'wpblock_options' ); ?>
			            <?php $user_htaccess = get_option( 'wpblock_user_htaccess' ); ?>
			            <?php $blocklist = get_option( 'wpblock_blocklist' ); ?>
			            <?php $permissions = get_option( 'wpblock_permissions' ); ?>
			            <br>
			            <?php _e( 'Extra .htaccess settings', 'wpblock' ); ?><hr>
			            <br>
			            <textarea rows="4" cols="50" name="wpblock_user_htaccess[user_htaccess]" type="textarea" placeholder="IP"><?php echo esc_html( $user_htaccess['user_htaccess']); ?></textarea>
			            <br>
			            <?php do_settings_sections( 'wpblock_options' );  ?>

			            <?php 

			            $spam_lists = [
							'malware' => 'Block malware/botnets',
							'spam' => 'Block spammers',
							'troll' => 'Block trolls',
							'government' => 'Block government',
							'proxy' => 'Block anonymous/proxy users',
							'uk' => 'Block all UK users',
							'russ' => 'Block all Russian users',
							'usa' => 'Block Amercian users'
						];
					    
					    foreach ($spam_lists as $list => $desc) {
					    	$output = '<input type="checkbox" name="wpblock_blocklist[blocklist][' . $list . ']" '; 
							if (isset($blocklist['blocklist'][$list]) && $blocklist['blocklist'][$list] == 1) { 
									$output .= 'value="0"';
									$output .= 'checked';
								} else {
									$output .= 'value="1"';
								}
								$output .= '>' . $desc . '<br>';
							echo $output;	
					    }

					    settings_fields( 'wpblock_permission_settings' );
					    print '<hr>';
						_e( 'Settings (Privacy and transparency controls)', 'wpblock' );
						echo '<br>';

					    $perms = [
							'consent_logs' => 'I consent to analysing access logs',
							'consent_requests' => 'I consent to analysing requests',
							'consent_system' => 'I consent to analysing system/server settings'
						];
					    
					    foreach ($perms as $perm => $desc) {
					    	$output = '<input type="checkbox" name="wpblock_permissions[permissions][' . $perm . ']" '; 
							if (isset($permissions['permissions'][$perm]) && $permissions['permissions'][$perm] == 1) { 
									$output .= 'value="0"';
									$output .= 'checked';
								} else {
									$output .= 'value="1"';
								}
								$output .= '>' . $desc . '<br>';
							echo $output;	
					    }
						submit_button(); 

						?>         
			        </form>
			<?php
			}

		}
}
