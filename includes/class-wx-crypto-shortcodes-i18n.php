<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://blog.wazirx.com/
 * @since      1.0.0
 *
 * @package    Wx_Crypto_Shortcodes
 * @subpackage Wx_Crypto_Shortcodes/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wx_Crypto_Shortcodes
 * @subpackage Wx_Crypto_Shortcodes/includes
 * @author     Sunny Luthra <luthra.sunny@gmail.com>
 */
class Wx_Crypto_Shortcodes_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wx-crypto-shortcodes',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
