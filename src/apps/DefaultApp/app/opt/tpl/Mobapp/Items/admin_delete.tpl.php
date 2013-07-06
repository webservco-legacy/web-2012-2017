<article>
	<?php if (!empty($strings['message'])) { ?>
	
	<p><?php echo $strings['message']; ?></p>
	
	<p>
		<a href="<?php echo $url['www']; ?>Items/Admin/Delete/id/<?php echo $item_id; ?>/confirm/1" title="<?php echo __('Delete'); ?>"><?php echo __('Delete'); ?></a>
		&#160;
		<a href="<?php echo $url['www']; ?>Items/Admin/Items" title="<?php echo __('Cancel'); ?>"><?php echo __('Cancel'); ?></a>
	</p>
	
	<?php
	} else { 
		
		echo $strings['no_data'];
	}
	?>
</article>