<?php

/**
 *
 * @link              https://wazirx.com/blog/wazirx-free-crypto-widgets/
 * @since             1.0.2
 * @package           Wx_Crypto_Shortcodes
 *
 * @wordpress-plugin
 * Plugin Name:       Crypto Price Widgets
 * Plugin URI:        https://wazirx.com/blog/wazirx-free-crypto-widgets/
 * Description:       This plugin empowers you to add ticker, charts, price table & calculators related to cryptocurrencies in your posts or pages.
 * Version:           1.0.1
 * Author:            WazirX
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wx-crypto-shortcodes
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WX_CRYPTO_SHORTCODES_VERSION', '1.0.1' );
define( 'WX_CRYPTO_API', 'https://api.wazirx.com/sapi/v1/' );
define( 'WX_CRYPTO_CANDLE_API', 'https://x.wazirx.com/api/v2/k?market={market}&period={period}&limit={limit}&timestamp={timestamp}' );
define( 'WX_CRYPTO_TRANSIENT_KEY', 'WX-CS' );
define( 'WX_CRYPTO_TRANSIENT_CACHE', 3600 );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wx-crypto-shortcodes-activator.php
 */
function activate_wx_crypto_shortcodes() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wx-crypto-shortcodes-activator.php';
	Wx_Crypto_Shortcodes_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wx-crypto-shortcodes-deactivator.php
 */
function deactivate_wx_crypto_shortcodes() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wx-crypto-shortcodes-deactivator.php';
	Wx_Crypto_Shortcodes_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wx_crypto_shortcodes' );
register_deactivation_hook( __FILE__, 'deactivate_wx_crypto_shortcodes' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wx-crypto-shortcodes.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wx_crypto_shortcodes() {

	$plugin = new Wx_Crypto_Shortcodes();
	$plugin->run();

}
run_wx_crypto_shortcodes();

if(!function_exists('wx_c_debug')){
	function wx_c_debug($what){
		echo '<pre>';
		print_r($what);
		echo '</pre>';
	} 
}