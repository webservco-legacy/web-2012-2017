<article>
		
<?php if (!empty($user['Logged'])) { ?>

		<p><?php echo __('Welcome'); ?> <strong><?php echo $user['Prop']['user_name_first']; ?></strong>!</p>
		
		<p>
			<a href="<?php echo $url['www']; ?>Users/App/Logout" title="<?php echo __('Logout'); ?>"><?php echo __('Logout'); ?></a>
		</p>
<?php } else { ?>
		
		<p><?php echo $string_no_data; ?></p>
<?php } ?>

</article>