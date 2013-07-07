<article>

	<form class="" method="get" action="<?php echo $url['www']; ?>Items/Admin/Items/" autocomplete="off">
		
		<p>
		
		<?php echo __('Status'); ?>:&#160;
		<select name="filter_status" id="filter_status">
			<option value="0">---</option>
			<option value="on"<?php if (!empty($query['filter_status']) && 'on' == $query['filter_status']) {echo ' selected="selected"';} ?>><?php echo __('on'); ?></option>
			<option value="off"<?php if (!empty($query['filter_status']) && 'off' == $query['filter_status']) {echo ' selected="selected"';} ?>><?php echo __('off'); ?></option>
		</select>
		
		&#160;&#160;&#160;
		<input name="filter" id="filter" type="submit" value="<?php echo __('Filter'); ?>">
		&#160;
		<a class="button" href="<?php echo $url['www']?>Items/Admin/items" title="<?php echo __('Clear'); ?>"><?php echo __('Clear'); ?></a>
		&#160;
		<a class="button" href="<?php echo $url['www']; ?>Items/Admin/Add" title="<?php echo __('Add item'); ?>"><?php echo __('Add item'); ?></a>
		</p>
	</form>
	
	<?php
	if (!empty($items['data']['current'])) {
		
		echo '<table class="items">';
	
		echo '<tr>';
		echo '<th>';
		echo __('Id');
		echo '</th>';
		echo '<th>';
		echo __('Title');
		echo '</th>';
		echo '<th>';
		echo __('Status');
		echo '</th>';
		echo '<th>';
		echo __('Options');
		echo '</th>';
		echo '</tr>';
		
		foreach ($items['data']['current'] as $item) {
			
			echo '<tr>';
			echo '<td>';
			echo "{$item['item_id']}";
			echo '</td><td>';
			echo "{$item['item_title']}";
			echo '</td><td>';
			echo __($item['item_status']);
			echo '</td><td>';
			echo "<a class=\"button\" href=\"{$url['www']}Items/Admin/Modify/id/{$item['item_id']}/next/{$url_return}\">" . __('Modify') . '</a>';
			echo '&#160;&#160;&#160;';
			echo "<a class=\"button\" href=\"{$url['www']}Items/Admin/Delete/id/{$item['item_id']}/next/{$url_return}\">".__('Delete')."</a>";
			echo '</td>';
			echo '</tr>';
		}
		
		echo '</table>';
		
		echo $tpl_pagination;
		
	} else {
		
		echo $strings['no_data'];
	}
	?>
</article>