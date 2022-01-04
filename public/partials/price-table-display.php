<?php
/**
 * The template for displaying the ticker
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// wx_c_debug($table_data);
?>
<?php $id = 'wx-table-' . uniqid(); ?>

<table id="<?php echo $id; ?>" style="width:100%">
		<thead>
			<tr>
				<th>Code</th>
				<th>Price</th>
				<th>Change(24h)</th>
				<th>Volume</th>
				<th>Buy</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $table_data as $row ) : ?>
			<tr class="<?php echo $row['status']; ?>">
				<td class="code">
						<img alt="<?php echo $row['name']; ?>" src="<?php echo $row['logo']; ?>"/>
						<?php echo $row['baseAsset']; ?>/<?php echo $row['quoteAsset']; ?>
				</td>
				<td>₹<?php echo $row['lastPrice']; ?></td>
				<td class="change"><?php echo $row['change']; ?> (<?php echo $row['change'] < 0 ? '-' : ''; ?><?php echo $row['change_percentage']; ?>)</td>
				<td>₹<?php echo $row['volume']; ?></td>
				<td class="v-middle"><a target="_BLANK" href="https://wazirx.com/exchange/<?php echo $row['name']; ?><?php echo $affiliate_code; ?>" class="button">Buy On WazirX</a></td>
			</tr>
		   <?php endforeach; ?>

		</tbody>
		<tfoot>
			<tr>
			<th>Code</th>
				<th>Price</th>
				<th>Change(24h)</th>
				<th>Volume</th>
				<th>Buy</th>

			</tr>
		</tfoot>
	</table>

<script>
(function( $ ) {
	$('#<?php echo $id; ?>').DataTable({
		"paging":   true,
		"ordering": false,
		"info":     false,
		"responsive": true
	});

})( jQuery );
</script>
