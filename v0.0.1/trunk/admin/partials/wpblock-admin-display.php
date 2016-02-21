<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wpblock
 * @subpackage wpblock/admin/partials
 */
	
	/**
	 * Require main Wpblock class
	 */
	require_once plugin_dir_path( __FILE__ ) . '../../includes/Wpblock.php';

	function extra_post_info_page(){
?>
  <h1>WordPress Extra Post Info</h1>
  <form method="post" action="options.php">
    <?php settings_fields( 'extra-post-info-settings' ); ?>
    <?php do_settings_sections( 'extra-post-info-settings' ); ?>
    <table class="form-table">
      <tr valign="top">
      <th scope="row">Extra post info:</th>
      <td><input type="text" name="extra_post_info" value="<?php echo get_option( 'extra_post_info' ); ?>"/></td>
      </tr>
    </table>
    <?php submit_button(); ?>
  </form>

<?php
}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<section id="main">
	<?php extra_post_info_page(); ?>
	<header>
		<h1>wpblock</h1>
		<p>WPBlock is a Wordpress plugin that protects you against known threats, attackers and automated bots by blacklisting IPs in real-time.</p>
		<?php  echo "Server type: " . Wpblock::get_server_type(); ?>
	</header>
	<section id="settings">
		<div id="form">
			<p>Enter the IP you want to blacklist</p><br>
			<input type="text" placeholder="e.g 127.0.0.1" id="ip">
			<button onclick="say();">Save</button>	
		</div>
		<p id="stat">Currently over <span>123,000</span> IPs and <span>1,000,000</span> malicous requests blocked</p>
		<div id="form">
			
			<p>What block-lists do you want to use?</p><hr>
			<input type="checkbox" name="" value="" checked>Block known malware and botnet IPs<br>
			<input type="checkbox" name="" value="" checked>Block known spammers<br>
			<input type="checkbox" name="" value="" checked>Block known troll IPs<br>
			<input type="checkbox" name="" value="" checked>Block known government IPs<br>
			<input type="checkbox" name="" value="" checked>Block anonymous networks and known proxy servers<br>
			<p>Block a specific country</p><hr>
			<ul>
				<li><input type="checkbox" name="" value="" checked>UK</li>
				<li><input type="checkbox" name="" value="" checked>Russia</li>
				<li><input type="checkbox" name="" value="" checked>USA</li>
			</ul>

			<p>Settings (Privacy and transparency controls)</p><hr>
			<input type="checkbox" name="" value="" checked>I consent to analysing access logs<br>
			<input type="checkbox" name="" value="" checked>I consent to analysing requests, cookies, sessions and IP addresses<br>
			<input type="checkbox" name="" value="" checked>I consent to analysing system/server settings<br>
		</div>
	</section>
</section>


<script type="text/javascript">
	function say() {
		var text = document.getElementById("ip").value;
		alert("Added "+text+" to block-list");
	}
</script>
