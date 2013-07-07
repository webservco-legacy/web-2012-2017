<article>
	<?php
	if (!empty($item)) {
		
		echo "<strong>{$item['user_name_first']} {$item['user_name_last']}</strong><br />{$item['user_email']}<br />";
		
	} else {
		
		echo $strings['no_data'];
	}
	?>
</article>