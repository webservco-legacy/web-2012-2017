<article>

	<p>
		<a href="<?php echo $url['www']; ?>Users/Admin/Users"><?php echo __('Users'); ?></a>
	</p>
	
	<?php
	
	if (!empty($item)) {
		
		echo "<strong>{$item['user_name_first']} {$item['user_name_last']}</strong><br />{$item['user_email']}<br />";
		
	} else {
		
		echo $string_no_data;
	}
	?>
</article>