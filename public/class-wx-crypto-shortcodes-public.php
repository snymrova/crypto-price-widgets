<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link  https://blog.wazirx.com/
 * @since 1.0.0
 *
 * @package    Wx_Crypto_Shortcodes
 * @subpackage Wx_Crypto_Shortcodes/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wx_Crypto_Shortcodes
 * @subpackage Wx_Crypto_Shortcodes/public
 * @author     Sunny Luthra <luthra.sunny@gmail.com>
 */
class Wx_Crypto_Shortcodes_Public {


	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wx-crypto-shortcodes-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-dataTables-css', plugin_dir_url( __FILE__ ) . 'css/datatables.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name . '-dataTables-js', plugin_dir_url( __FILE__ ) . 'js/datatables.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '-apex', plugin_dir_url( __FILE__ ) . 'js/apexcharts.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wx-crypto-shortcodes-public.js', array( $this->plugin_name . '-apex' ), $this->version, false );
		wp_localize_script(
			$this->plugin_name,
			'wx_crypto_shortcode_charts_api',
			array(
				'url'            => site_url( 'wp-json/' . $this->plugin_name . '/v1/data' ),
				'affiliate_code' => $this->get_affiliate_code(),
				/**
				 * Create nonce for security.
				 *
				 * @link https://codex.wordpress.org/Function_Reference/wp_create_nonce
				 */
				'_nonce'         => wp_create_nonce( $this->plugin_name ),

			)
		);

	}

	 /**
   * Crypto coin ticker
   */
  public function ticker($atts)
  {
    $a = shortcode_atts(
      [
        "referral_code" => "",
        "source" => "crypto-widgets-wp-plugin",
        "campaign" => "cw-wpplug-crypto-ticker",
      ],
      $atts
    );
    $ticker_data = $this->fetch_ticker();
    $content = "";
    if ($ticker_data) {
      // wx_c_debug($ticker_data);
      $ticker = $this->prepare_ticker_data($ticker_data);
      if ($a["referral_code"]) {
        $affiliate_code = $this->get_affiliate_code_from_shortcode(
          $a["referral_code"], $a['source'], $a['campaign']
        );
      } else {
        $affiliate_code = $this->get_affiliate_code($a['source'], $a['campaign']);
      }

      ob_start();
      include plugin_dir_path(__FILE__) . "partials/ticker-display.php";
      $content = ob_get_clean();
    }
    return $content;
  }
  /**
   * Fetch ticker data from WX APi
   */
  private function fetch_ticker($symbol = "")
  {
    $t_key = WX_CRYPTO_TRANSIENT_KEY;

    if ($symbol) {
      $ticker_api = WX_CRYPTO_API . "ticker/24hr?symbol=" . $symbol;
      $transient_ticker_key = "{$t_key}-{$symbol}-ticker";
    } else {
      $ticker_api = WX_CRYPTO_API . "tickers/24hr";
      $transient_ticker_key = "{$t_key}-ticker";
    }
    $ticker = get_site_transient($transient_ticker_key);
    if ($ticker) {
      return $ticker;
    }
    $ticker_data = wp_remote_get($ticker_api);
    $ticker = "";
    if (!is_wp_error($ticker_data)) {
      $ticker = json_decode(wp_remote_retrieve_body($ticker_data), true);
      // if symbol is active then api will return data without array closure
      if ($symbol) {
        $ticker = [$ticker];
      }
      set_site_transient(
        $transient_ticker_key,
        $ticker,
        WX_CRYPTO_TRANSIENT_CACHE
      );
    }

    return $ticker;
  }
  /**
   * Prepare the array of ticker data
   */
  public function prepare_ticker_data($ticker_data)
  {
    $ticker = [];
    foreach ($ticker_data as $tick) {
      $ticker[] = $this->prepare_single_tick($tick);
    }
    return $ticker;
  }
  /**
   * Prepare single tick
   */
  public function prepare_single_tick($tick)
  {
    $tick["name"] = $tick["baseAsset"] . "-" . $tick["quoteAsset"];
    $tick["change"] = $tick["lastPrice"] - $tick["openPrice"];
    $tick["status"] = $tick["change"] < 0 ? "loss" : "gain";
    $tick["change_percentage"] =
      (abs($tick["change"]) / $tick["openPrice"]) * 100;

    $tick["change"] = $this->format_currency(
      $this->format_number($tick["change"], 8, true)
    );
    $tick["change_percentage"] =
      $this->format_number($tick["change_percentage"], 2) . "%";
    $tick["logo"] =
      "https://media.wazirx.com/media/" . $tick["baseAsset"] . "/84.png";
    return $tick;
  }
  public function format_number($number, $dec = 0, $trim = true)
  {
    if ($trim) {
      $parts = explode(".", round($number, $dec) * 1);
      $dec = isset($parts[1]) ? strlen($parts[1]) : 0;
    }
    $formatted = number_format($number, $dec);
    return $formatted;
  }
  public function format_currency($amount, $curr = "₹")
  {
    if ($amount < 0) {
      return "-" . $curr . abs($amount);
    }

    return $curr . $amount;
  }
  /**
   * Price table shortcode
   * [wx-crypto-price-table]
   */
  public function price_table($atts)
  {
    $a = shortcode_atts(["referral_code" => "",  "source" => "crypto-widgets-wp-plugin",
	"campaign" => "cw-wpplug-crypto-price-table"], $atts);
    $ticker_data = $this->fetch_ticker();
    $content = "";
    if ($ticker_data) {
      // wx_c_debug($ticker_data);
      $table_data = $this->prepare_ticker_data($ticker_data);
      if ($a["referral_code"]) {
        $affiliate_code = $this->get_affiliate_code_from_shortcode(
          $a["referral_code"], $a['source'], $a['campaign']
        );
      } else {
        $affiliate_code = $this->get_affiliate_code($a['source'], $a['campaign']);
      }
      ob_start();
      include plugin_dir_path(__FILE__) . "partials/price-table-display.php";
      $content = ob_get_clean();
    }
    return $content;
  }
  /**
   * Price Chart
   * [wx-crypto-price-chart]
   */
  public function price_chart($atts)
  {
    $a = shortcode_atts(
      [
        "market" => "btcinr",
        "title" => "",
        "referral_code" => "",
		"source" => "crypto-widgets-wp-plugin",
	"campaign" => "cw-wpplug-crypto-price-chart"
      ],
      $atts
    );
    if ($a["referral_code"]) {
      $affiliate_code = $this->get_affiliate_code_from_shortcode(
        $a["referral_code"],$a['source'], $a['campaign']
      );
    } else {
      $affiliate_code = $this->get_affiliate_code($a['source'], $a['campaign']);
    }

    if ($a["title"] == "") {
      $a["title"] = $a["market"];
    }

    ob_start();
    include plugin_dir_path(__FILE__) . "partials/price-chart-display.php";
    $content = ob_get_clean();
    return $content;
  }
  /**
   * Converter widget
   * [wx-crypto-converter]
   */
  public function converter($atts)
  {
    $a = shortcode_atts(
      [
        "width" => "500px",
        "height" => "317px",
		"referral_code" => "",
		"source" => "crypto-widgets-wp-plugin",
	"campaign" => "cw-wpplug-crypto-converter"
      ],
      $atts
    );
	if ($a["referral_code"]) {
		$affiliate_code = $this->get_affiliate_code_from_shortcode(
		  $a["referral_code"],$a['source'], $a['campaign']
		);
	  } else {
		$affiliate_code = $this->get_affiliate_code($a['source'], $a['campaign']);
	  }
    $converter_link = "https://tools.wazirx.com/widgets/crypto-converter" . $affiliate_code;
    ob_start();
    include plugin_dir_path(__FILE__) . "partials/converter.php";
    $content = ob_get_clean();
    return $content;
  }

  /**
   * Return Converter widget
   * [wx-crypto-return-converter]
   */
  public function return_converter($atts)
  {
    $a = shortcode_atts(
      [
        "width" => "500px",
        "height" => "459px",
		"referral_code" => "",
		"source" => "crypto-widgets-wp-plugin",
	"campaign" => "cw-wpplug-crypto-roi-calculator"
      ],
      $atts
    );
	if ($a["referral_code"]) {
		$affiliate_code = $this->get_affiliate_code_from_shortcode(
		  $a["referral_code"],$a['source'], $a['campaign']
		);
	  } else {
		$affiliate_code = $this->get_affiliate_code($a['source'], $a['campaign']);
	  }
    $converter_link = "https://tools.wazirx.com/widgets/crypto-roi". $affiliate_code; 
    ob_start();
    include plugin_dir_path(__FILE__) . "partials/return-converter.php";
    $content = ob_get_clean();
    return $content;
  }

  /**
   * Past Performance Calculator
   * [wx-crypto-pp-calculator]
   */
  public function pp_calculator($atts)
  {
    $a = shortcode_atts(
      [
        "width" => "500px",
        "height" => "456px",
		"referral_code" => "",
		"source" => "crypto-widgets-wp-plugin",
	"campaign" => "cw-wpplug-crypto-past-performance"
      ],
      $atts
    );
	if ($a["referral_code"]) {
		$affiliate_code = $this->get_affiliate_code_from_shortcode(
		  $a["referral_code"],$a['source'], $a['campaign']
		);
	  } else {
		$affiliate_code = $this->get_affiliate_code($a['source'], $a['campaign']);
	  }
    $converter_link = "https://tools.wazirx.com/widgets/past-performance" . $affiliate_code;
    ob_start();
    include plugin_dir_path(__FILE__) . "partials/pp-calculator.php";
    $content = ob_get_clean();
    return $content;
  }

  /**
   * Create end point for chart data
   */
  public function charts_api()
  {
    register_rest_route($this->plugin_name . "/v1", "/data", [
      "methods" => "GET",
      "callback" => [$this, "charts_get_data"],
      "permission_callback" => "__return_true",
    ]);
  }

  public function charts_get_data()
  {
    $api = WX_CRYPTO_CANDLE_API;
    $t_key = WX_CRYPTO_TRANSIENT_KEY;

    $date = new DateTime();
    $interval = "1D";
    $cache = WX_CRYPTO_TRANSIENT_CACHE;
    if (isset($_GET["time-interval"])) {
      $_interval = sanitize_text_field($_GET["time-interval"]);
      if ($this->verify_chart_interval($_interval)) {
        $interval = $_interval;
      }
    }

    if (isset($_GET["market"])) {
      $market = esc_sql($_GET["market"]);
    } else {
      $market = "btcinr";
    }

    if ($interval == "1H") {
      $date_hours_to_subtract = "PT{$interval}";
    } else {
      $date_hours_to_subtract = "P{$interval}";
    }

    $period = [
      "1H" => 1,
      "1D" => 60,
      "1W" => 360,
      "1M" => 720,
      "6M" => 1440,
      "1Y" => 1440,
      "10Y" => 1440,
    ];

    $date->sub(new DateInterval($date_hours_to_subtract));

    $args = [
      "{market}" => $market,
      "{period}" => $period[$interval],
      "{limit}" => 2000,
      "{timestamp}" => $date->getTimestamp(),
    ];

    $search_for = array_keys($args);
    $replace_with = array_values($args);

    $candle_endpoint = str_replace($search_for, $replace_with, $api);

    $transient_key = "{$t_key}-{$interval}-{$market}";

    $result_exist = get_site_transient($transient_key);

    if ($result_exist) {
      return new WP_REST_Response($result_exist);
    }

    $request = wp_remote_get($candle_endpoint);

    $response_code = wp_remote_retrieve_response_code($request);
    $response_message = wp_remote_retrieve_response_message($request);
    $response_body = wp_remote_retrieve_body($request);
    if (!is_wp_error($request)) {
      // Retrieve information

      $e = json_decode($response_body);
      $entries = [];
      foreach ($e as $v) {
        $_e = [$v[0] * 1000, $v[4]];
        $entries[] = $_e;
      }

      $return = [
        "title" => $market,
        "market" => $args["{market}"],
        "period" => $args["{period}"],
        "limt" => $args["{limit}"],
        "timestamp" => $args["{timestamp}"],
        "entries" => $entries,
      ];

      $ticker = $this->fetch_ticker($market);
      if ($ticker) {
        $ticker = $this->prepare_ticker_data($ticker);
        $ticker = $ticker[0];
      }

      $return["ticker"] = $ticker;
      $transient_value = $return;
      set_site_transient($transient_key, $transient_value, $cache);
      return new WP_REST_Response($return);
    } else {
      return new WP_Error($response_code, $response_message, $response_body);
    }
  }
  public function verify_chart_interval($interval)
  {
    $allowed_intervals = ["1H", "1D", "1W", "1M", "6M", "1Y", "10Y"];
    if (in_array($interval, $allowed_intervals)) {
      return true;
    }
    return false;
  }

  public function get_affiliate_code($source = "", $campaign = "")
  {
    $is_referral_on = carbon_get_theme_option("wxcs_turn_referral_on");
    $referral_code = "";
    if ($is_referral_on) {
      $code = esc_attr(carbon_get_theme_option("wxcs_referral_invite_code"));
      if ($code) {
        $referral_code = "?invite=" . $code;
      }
    }
    if (!$referral_code) {
		$referral_code = "?";
    }else{
		$referral_code = $referral_code . "&";
	}
	$source = esc_attr($source);
	$campaign = esc_attr($campaign);
	$referral_code =
	  $referral_code .
	  "utm_source=${source}&utm_medium=referral&utm_campaign=${campaign}";
 
    return $referral_code;
  }
  public function get_affiliate_code_from_shortcode(
    $code,
    $source = "",
    $campaign = ""
  ) {
    $code = esc_attr($code);
    $referral_code = "";
    if ($code) {
      $referral_code = "?invite=" . $code;
    }
    if (!$referral_code) {
		$referral_code = "?";
    }else{
		$referral_code = $referral_code . "&";
	}
	$source = esc_attr($source);
	$campaign = esc_attr($campaign);
	$referral_code =
	  $referral_code .
	  "utm_source=${source}&utm_medium=referral&utm_campaign=${campaign}";

    return $referral_code;
  }
}
