<article>
	<?php
	if (!empty($items)) {
		
		foreach ($items as $id => $item) {
			
			echo "{$id}: <a href=\"{$url['www']}Items/Admin/Modify/id/{$id}\"><strong>{$item['item_title']}</strong></a>";
			echo "&#160;&#160;&#160;<a href=\"{$url['www']}Items/Admin/Delete/id/{$id}\">[".__('Delete')."]</a><br />";
		}
		
		echo "<br /><a class=\"button\" href=\"{$url['www']}Items/Admin/Add\">".__('Add item')."</a>";
		
	} else {
		
		echo $strings['no_data'];
	}
	?>
</article>