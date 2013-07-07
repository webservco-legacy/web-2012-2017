<article>
	<?php if (!empty($strings['message'])) { ?>
	
	<p><?php echo $strings['message']; ?></p>
	
	<p>
		<a class="button" href="<?php echo $url['www']; ?>Items/Admin/Delete/id/<?php echo $item_id; ?>/confirm/1<?php echo $url['next_string']; ?>" title="<?php echo __('Delete'); ?>"><?php echo __('Delete'); ?></a>
		&#160;
		<a class="button" href="<?php echo $url['items_admin']; ?>" title="<?php echo __('Cancel'); ?>"><?php echo __('Cancel'); ?></a>
	</p>
	
	<?php
	} else { 
		
		echo $strings['no_data'];
	}
	?>
</article>