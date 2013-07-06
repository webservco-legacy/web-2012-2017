<article>
	<?php
	if (!empty($items)) {
		
		foreach ($items as $id => $item) {
			
			echo "{$id}: <a href=\"{$url['www']}Items/Admin/Modify/id/{$id}\"><strong>{$item['item_title']}</strong></a>";
			echo "&#160;<a href=\"{$url['www']}Items/Admin/Delete/id/{$id}\">".__('Delete')."</a><br />";
		}
		
	} else {
		
		echo $strings['no_data'];
	}
	?>
</article>