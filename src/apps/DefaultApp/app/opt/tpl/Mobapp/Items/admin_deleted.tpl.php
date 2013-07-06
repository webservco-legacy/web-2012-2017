<article>
	<?php if (!empty($item_id) && !empty($strings['message'])) { ?>
	
	<p><?php echo $strings['message']; ?></p>
	
	<p>
		<a href="<?php echo $url['www']; ?>Items/Admin/Items" title="<?php echo __('Items'); ?>"><?php echo __('Items'); ?></a>
	</p>
	
	<?php
	} else { 
		
		echo $strings['no_data'];
	}
	?>
</article>