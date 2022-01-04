<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://blog.wazirx.com/
 * @since      1.0.0
 *
 * @package    Wx_Crypto_Shortcodes
 * @subpackage Wx_Crypto_Shortcodes/includes
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
 * @package    Wx_Crypto_Shortcodes
 * @subpackage Wx_Crypto_Shortcodes/includes
 * @author     Sunny Luthra <luthra.sunny@gmail.com>
 */
use Carbon_Fields\Container;
use Carbon_Fields\Field;
class Wx_Crypto_Shortcodes {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wx_Crypto_Shortcodes_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
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
		if ( defined( 'WX_CRYPTO_SHORTCODES_VERSION' ) ) {
			$this->version = WX_CRYPTO_SHORTCODES_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wx-crypto-shortcodes';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wx_Crypto_Shortcodes_Loader. Orchestrates the hooks of the plugin.
	 * - Wx_Crypto_Shortcodes_i18n. Defines internationalization functionality.
	 * - Wx_Crypto_Shortcodes_Admin. Defines all hooks for the admin area.
	 * - Wx_Crypto_Shortcodes_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wx-crypto-shortcodes-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wx-crypto-shortcodes-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wx-crypto-shortcodes-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wx-crypto-shortcodes-public.php';

		/**
		 * The class responsible for loading thrid party frameworks
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';
		\Carbon_Fields\Carbon_Fields::boot();

		$this->loader = new Wx_Crypto_Shortcodes_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wx_Crypto_Shortcodes_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wx_Crypto_Shortcodes_i18n();

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

		$plugin_admin = new Wx_Crypto_Shortcodes_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'carbon_fields_register_fields', $plugin_admin, 'plugin_options' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wx_Crypto_Shortcodes_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_shortcode( 'wx-crypto-ticker', $plugin_public, 'ticker' );
		$this->loader->add_shortcode( 'wx-crypto-price-table', $plugin_public, 'price_table' );

		$this->loader->add_action( 'rest_api_init', $plugin_public, 'charts_api' );
		$this->loader->add_shortcode( 'wx-crypto-price-chart', $plugin_public, 'price_chart' );

		$this->loader->add_shortcode( 'wx-crypto-converter', $plugin_public, 'converter' );
		$this->loader->add_shortcode( 'wx-crypto-return-calculator', $plugin_public, 'return_converter' );
		$this->loader->add_shortcode( 'wx-crypto-pp-calculator', $plugin_public, 'pp_calculator' );

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
	 * @return    Wx_Crypto_Shortcodes_Loader    Orchestrates the hooks of the plugin.
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

}
