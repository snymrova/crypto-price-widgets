<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://blog.wazirx.com/
 * @since      1.0.0
 *
 * @package    Wx_Crypto_Shortcodes
 * @subpackage Wx_Crypto_Shortcodes/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wx_Crypto_Shortcodes
 * @subpackage Wx_Crypto_Shortcodes/admin
 * @author     Sunny Luthra <luthra.sunny@gmail.com>
 */
use Carbon_Fields\Container;
use Carbon_Fields\Field;
class Wx_Crypto_Shortcodes_Admin {

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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

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
		 * defined in Wx_Crypto_Shortcodes_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wx_Crypto_Shortcodes_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wx-crypto-shortcodes-admin.css', array(), $this->version, 'all' );

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
		 * defined in Wx_Crypto_Shortcodes_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wx_Crypto_Shortcodes_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wx-crypto-shortcodes-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function plugin_options() {
		$path = plugin_dir_url( __FILE__ );
		Container::make( 'theme_options', __( 'WRX Crypto Widgets' ) )
		->set_icon( 'dashicons-embed-generic' )
		->set_page_file( 'wx-crypto-shortocodes-options' )
		->add_fields(
			array(
				Field::make( 'html', 'wxcs-description', __( 'Section Description' ) )
				->set_html(
					__(
						'
				<h2>This plugin empowers you to add <b>ticker, charts, price table & calculators</b> related to cryptocurrencies in your posts or pages.</h2>
				<h2><b>You can also join <a href="https://wazirx.com/referral" target="_blank">WazirX Referral Program</a> and Earn 50% commission in WRX on every trade!</b></h2> 
			'
					)
				),
				Field::make( 'checkbox', 'wxcs_turn_referral_on', __( 'I want to earn 50% on every trade!' ) )
				->set_option_value( 'yes' ),
				Field::make( 'text', 'wxcs_referral_invite_code', __( 'Your Code' ) )
				->set_help_text( 'Enter you code here. Please register at <a href="https://wazirx.com/referral" target="_blank">WazirX Referral Program</a> if you don\'t have your referral code.' )
				->set_conditional_logic(
					array(
						array(
							'field' => 'wxcs_turn_referral_on',
							'value' => true,
						),
					)
				),
				Field::make( 'html', 'wxcs-shortcodes-description', __( 'Shortcodes' ) )
				->set_html(
					__(
						'
				These are the shortcodes that you can use in any post or page.
				<h2>&#8211; <b>[wx-crypto-ticker]</b></h2>
				<img src="'.$path.'/images/ticker.png"/>
				<hr/>
				<h2>&#8211; <b>[wx-crypto-price-table]</b></h2>
				<img style="max-width:800px" src="'.$path.'/images/pricetable.png"/>
				<hr/>
				<h2>&#8211; <b>[wx-crypto-price-chart]</b></h2>
				<h4>By default the BTCINR price chart will be renedered. To rendere chart for ethereum or anyother coin just pass the symbol like this: [wx-crypto-price-chart market=ethinr] </h4>
				<img src="'.$path.'/images/pricechart.png"/>
				<hr/>
				<h2>&#8211; <b>[wx-crypto-converter]</b></h2>
				<img src="'.$path.'/images/bitcoincryptoconverter.png"/>
				<hr/>
				<h2>&#8211; <b>[wx-crypto-return-calculator]</b></h2>
				<img src="'.$path.'/images/cryptoreturnscalculator.png"/>
				<hr/>
				<h2>&#8211; <b>[wx-crypto-pp-calculator]</b></h2>
				<img src="'.$path.'/images/cryptopastperformance.png"/>
				<hr/>
			'
					)
				),

			)
		);
	}

}
