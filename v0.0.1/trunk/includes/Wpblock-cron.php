<?php

/**
 * Fired once every hour
 *
 * @link       http://wpblock.io
 * @since      1.0.0
 *
 * @package    wpblock
 * @subpackage wpblock/includes
 */

/**
 *
 * This class downloads blocklist and writes htaccess
 *
 * @since      1.0.0
 * @package    wpblock
 * @subpackage wpblock/includes
 * @author     Your Name <porthunter@wpblock.io>
 */
class Wpblock_Cron {

	/**
	 * This class downloads blocklist and writes htaccess
	 *
	 * Fired once every hour
	 *
	 * @since    1.0.0
	 */
	public static function configure_cron() {

		// for dev only!!!
		wp_clear_scheduled_hook( 'update_htaccess');


		if (!wp_next_scheduled('update_htaccess')) {
		  wp_schedule_event( time(), 'hourly', 'update_htaccess' );
		}

		add_action( 'update_htaccess', 'write_htaccess' );

		function write_htaccess() {
		  	//wp_mail('you@yoursite.com', 'Automatic email', 'Hello, this is an automatically scheduled email from WordPress.');
			$response = wp_remote_get( 'https://raw.githubusercontent.com/porthunter/wpblock/master/blocklists/blocklist.txt' );
			if( is_array($response) ) {
				$file = ".htaccess";
				$handler = fopen($file, "w") or die("Unable to open file!");

				$txt = "<files ~ \"^.*\.([Hh][Tt][Aa])\"> \n";
				$txt .= "order allow,deny \n";
				$txt .= "deny from all \n";
				$txt .= "satisfy all \n";
				$txt .= "</files> \n";

				fwrite($handler, $txt);

				$txt = "Options All -Indexes \n";
				fwrite($handler, $txt);

				$txt = "Order Deny,Allow \n";
				fwrite($handler, $txt);

				$header = $response['headers']; // array of http header lines
				$body = $response['body']; // use the content
				foreach(preg_split("/((\r?\n)|(\r\n?))/", $body) as $line){
				    if ($line != "") {
				    	$txt = "Deny from " . $line . "\n";
						fwrite($handler, $txt);
				    }
				}
				fclose($handler);
			}

		}
	}

}
