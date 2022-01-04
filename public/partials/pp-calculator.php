<?php
/**
 * The template for displaying the ticker
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}
// wx_c_debug($ticker);
?>
<div class="wx-crypto-pp-calculator-wrapper">
	<iframe src="<?php echo esc_url($converter_link) ?>" style="border:0px;" name="wx-crypto-pp-calculator" scrolling="no" height="<?php echo esc_attr($a['height'])?>" width="<?php echo esc_attr($a['width'])?>"></iframe>
</div>
