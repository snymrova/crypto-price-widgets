<?php
/**
 * The template for displaying the ticker
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}
// wx_c_debug($chart_data);
?>
<?php $id = 'wx-crypto-shortocdes-chart-' . uniqid()?>
<div id="<?php echo esc_attr($id) ?>" data-market="<?php echo esc_attr($a['market']) ?>" data-referral_code="<?php echo esc_attr($affiliate_code) ?>">
	<div class="wx-crypto-shortcodes-ticker" style="display:none;">
		<h2 class="market-title">
			<a target="_BLANK" class="exchange-link market-code" href="" title="Buy on WazirX"></a>
		</h2>
		<b class="market-price"></b>
		<small class="details">
		(24H Low: <b class="low"></b><em class=""> | </em>24H High: <b class="high"></b>)
		</small>
		<a target="_BLANK" class="button exchange-link" href="" title="Buy on WazirX"><span>Buy on WazirX</span></a>

		<hr>
		<div class="toolbar">
			<a data-time-interval="1H" href="#" class="one_hour button">
						1H
			</a>
					<a data-time-interval="1D" href="#" class="one_day  button">
						1D
			</a>
					<a data-time-interval="1W" href="#" class="one_week button">
						1W
			</a>

			<a href="#" data-time-interval="1M" class="one_month button">
						1M
			</a>
					<a href="#" data-time-interval="6M" class="six_months button">
						6M
			</a>

					<a href="#" data-time-interval="1Y" class="one_year button ">
						1Y
			</a>
			<a href="#" data-time-interval="10Y" class="ten_years active button">
						YTD
			</a>
			<small style="display:none;" class="blink_me time-interval-loading">Loading...</small>
		</div>
	</div>
    <div class="chart"></div>
</div>
<script>


(function( $ ) {
  wx_crypto_shortcodes_render_chart("<?php echo esc_attr($id) ?>");
})( jQuery );



</script>
