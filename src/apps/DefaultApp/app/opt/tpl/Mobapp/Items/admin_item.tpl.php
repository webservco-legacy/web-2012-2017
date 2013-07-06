<article>

	<?php
	if (!empty($item_id)) {
		
		echo "<strong>{$item_id}</strong><br />";
		
	} else {
		
		echo $strings['no_data'];
	}
	?>
</article>