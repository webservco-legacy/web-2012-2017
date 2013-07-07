<article>
	<?php
	if (!empty($items)) {
	?>
	
	<form class="" method="get" action="<?php echo $url['www']; ?>index.php/Items/Admin/Items/" autocomplete="off">
		
		<p>
		
		<?php echo __('Status'); ?>:&#160;
		<select name="filter_status" id="filter_status">
			<option value="">---</option>
			<option value="on"<?php if (!empty($query['filter_status']) && 'on' == $query['filter_status']) {echo ' selected="selected"';} ?>><?php echo __('on'); ?></option>
			<option value="off"<?php if (!empty($query['filter_status']) && 'off' == $query['filter_status']) {echo ' selected="selected"';} ?>><?php echo __('off'); ?></option>
		</select>
		
		&#160;&#160;&#160;
		<input name="filter" id="filter" type="submit" value="<?php echo __('Filter'); ?>">
		
		</p>
	</form>
	
	<?php
		
		foreach ($items as $id => $item) {
			
			echo '<p>';
			echo "{$id}: <a href=\"{$url['www']}Items/Admin/Modify/id/{$id}\"><strong>{$item['item_title']}</strong></a>";
			echo "&#160;&#160;&#160;<a href=\"{$url['www']}Items/Admin/Delete/id/{$id}\">[".__('Delete')."]</a>";
			echo '</p>';
		}
		
		echo "<p><a class=\"button\" href=\"{$url['www']}Items/Admin/Add\">".__('Add item')."</a></p>";
		
	} else {
		
		echo $strings['no_data'];
	}
	?>
</article>