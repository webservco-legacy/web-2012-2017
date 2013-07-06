<article>

	<?php if (!empty($form)) { ?>
	
	<?php
	if (!empty($form['errors']['result']) || !empty($form['errors']['fields'])) {
		
		echo '<p>';
		echo '<strong>' . __('Error') . '!</strong><br />';
		
		if (!empty($form['errors']['result'])) {
			foreach ($form['errors']['result'] as $message) {
				echo "<strong>{$message}</strong><br />";
			}
		}
		echo '</p>';
	}
	?>
		
	<form class="" enctype="multipart/form-data" method="post" action="">
	
		<fieldset>
		
			<label for="item_status" title=""><?php echo __('Status'); ?></label>
			
			<p>
				<select name="item_status" id="item_status">
					<option value="on"<?php if (!empty($form['data']['item_status']) && 'on' == $form['data']['item_status']) {echo ' selected="selected"';} ?>><?php echo __('on'); ?></option>
					<option value="off"<?php if (!empty($form['data']['item_status']) && 'off' == $form['data']['item_status']) {echo ' selected="selected"';} ?>><?php echo __('off'); ?></option>
				</select>
				<?php
				if (!empty($form['errors']['fields']["item_status"])) {
					echo "<strong class=\"ko\">{$form['errors']['fields']["item_status"]}</strong>";	
				} ?>
			</p>
			
			<?php foreach ($i18n['langs'] as $lang => $locale) { ?>
			
			<label for="item_title_<?php echo $lang; ?>" title=""><?php echo __('Title'); ?> (<?php echo $lang; ?>)</label>
			
			<p>
				<input name="item_title_<?php echo $lang; ?>" id="item_title_<?php echo $lang; ?>" type="text" value="<?php echo !empty($form['data'][$locale]['item_title']) ? $form['data'][$locale]['item_title'] : NULL; ?>" />
				<?php
				if (!empty($form['errors']['fields']["item_title_{$lang}"])) {
					echo "<strong class=\"ko\">{$form['errors']['fields']["item_title_{$lang}"]}</strong>";	
				} ?>
			</p>
			
			<label for="item_description_<?php echo $lang; ?>" title=""><?php echo __('Description'); ?> (<?php echo $lang; ?>)</label>
			
			<p>
				<textarea name="item_description_<?php echo $lang; ?>" id="item_description_<?php echo $lang; ?>">
					<?php echo !empty($form['data'][$locale]['item_description']) ? $form['data'][$locale]['item_description'] : NULL; ?>
				</textarea>
				<?php
				if (!empty($form['errors']['fields']["item_description_{$lang}"])) {
					echo "<strong class=\"ko\">{$form['errors']['fields']["item_description_{$lang}"]}</strong>";	
				} ?>
			</p>
			
			<?php } ?>
		
			<label for="save" title=""></label>
			
			<p>
				<input name="add" id="add" type="submit" title="" value="<?php echo __('Add'); ?>" />
			</p>
		
		</fieldset>
	
	</form>
		
	<?php } else {
		
		echo $strings['no_data'];
	}
	?>
</article>