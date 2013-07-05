<article>
		
<?php if (!empty($error_message)) { ?>
	
		<p class="strong ko"><?php echo $error_message; ?></p>
		
<?php } else if (!empty($form)) { ?>
		
		<form class="login" enctype="multipart/form-data" method="post" action="">
		
			<fieldset>
					
			<?php foreach ($form['meta'] as $field_id => $meta) { ?>
				<label class="tooltip" for="<?php echo $field_id; ?>" title="<?php echo $meta['description']; ?>">
					<?php echo $meta['title']; ?>&#160;
										<?php if ('captcha' ==  $field_id) { ?>
					<img src="<?php echo $url_app; ?>Users/App/Captcha" title="<?php echo __('Captcha'); ?>" alt="<?php echo __('Captcha'); ?>" />
					<?php } ?>
				</label>
					
				<p>
					<input class="tooltip" title="<?php echo $meta['description']; ?>" type="<?php echo $meta['type']; ?>" name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>" value="<?php echo $form['data'][$field_id]; ?>" /> 
				</p>
					
				<?php if (!empty($form['errors'][$field_id])) { ?>
				<p class="form_error ko">
					<?php foreach ($form['errors'][$field_id] as $error_msg) {
						echo $error_msg . '<br />';
					} ?> 
				</p>
				<?php } ?>				
			<?php } ?>				
					
			</fieldset>		
		
		</form>
		
<?php } ?>

		<p class="clearfix">
			<?php echo __('Did you forget your password?'); ?>
			<br />
			<a href="<?php echo $url_app; ?>Users/App/ResetPassword" title="<?php echo __('Reset password'); ?>"><?php echo __('Reset password'); ?></a>
		</p>
		
		<p class="clearfix">
			<br />
			<?php echo __('Other options'); ?>:
			<br />
			<a href="<?php echo $url_app; ?>Users/App/Account" title="<?php echo __('Login'); ?>"><?php echo __('Login'); ?></a>
			<br />
			<a href="<?php echo $url_app; ?>Users/App/Register" title="<?php echo __('Register'); ?>"><?php echo __('Register'); ?></a>
		</p>
		
</article>		