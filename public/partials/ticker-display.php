<?php
/**
 * The template for displaying the ticker
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// wx_c_debug($ticker);
?>
<div class="wx-crypto-ticker-wrapper">
	<marquee onmouseover="this.stop();" onmouseout="this.start();" scrollamount='6'>
		<div class="wx-crypto-ticker">
			<?php foreach ( $ticker as $tick ) : ?>
			<div class="wx-crypto-tick <?php echo esc_attr($tick['status']); ?>">
				<a target="_BLANK" href="https://wazirx.com/exchange/<?php echo esc_attr($tick['name']); ?><?php echo esc_attr($affiliate_code); ?>" >
					<img src="<?php echo esc_attr($tick['logo']); ?>" alt="" class="wx-crypto-tick-logo">
					<span class="wx-crypto-tick-stats">
						<span class="wx-crypto-tick-stat"><?php echo esc_attr($tick['baseAsset']); ?><span class="wx-crypto-tick-quote">/<?php echo esc_attr($tick['quoteAsset']); ?></span>
						<span class="wx-crypto-tick-change-percentage"><?php echo $tick['change'] < 0 ? '-' : ''; ?><?php echo esc_attr($tick['change_percentage']); ?></span>
					</span>
					<span class="wx-crypto-tick-price">₹<?php echo esc_attr($tick['lastPrice']); ?></span>
				</a>
			</div>
			<?php endforeach; ?>
		</div>
	</marquee>
</div>
