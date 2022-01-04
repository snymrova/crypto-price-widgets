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
			<div class="wx-crypto-tick <?php echo $tick['status']; ?>">
				<a target="_BLANK" href="https://wazirx.com/exchange/<?php echo $tick['name']; ?><?php echo $affiliate_code; ?>" >
					<img src="<?php echo $tick['logo']; ?>" alt="" class="wx-crypto-tick-logo">
					<span class="wx-crypto-tick-stats">
						<span class="wx-crypto-tick-stat"><?php echo $tick['baseAsset']; ?><span class="wx-crypto-tick-quote">/<?php echo $tick['quoteAsset']; ?></span>
						<span class="wx-crypto-tick-change-percentage"><?php echo $tick['change'] < 0 ? '-' : ''; ?><?php echo $tick['change_percentage']; ?></span>
					</span>
					<span class="wx-crypto-tick-price">₹<?php echo $tick['lastPrice']; ?></span>
				</a>
			</div>
			<?php endforeach; ?>
		</div>
	</marquee>
</div>
