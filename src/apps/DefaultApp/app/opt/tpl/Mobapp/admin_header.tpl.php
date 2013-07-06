	<header>
		
		<article>
		
			<?php if (!empty($user['Logged'])) { ?>
				<strong><?php echo $user['Prop']['user_name_first']; ?></strong> <a href="<?php echo $url['www']; ?>Users/App/Logout" title="<?php echo __('Logout'); ?>"><?php echo __('Logout'); ?></a>
		<?php } else { ?>
		
				<a href="<?php echo $url['www']; ?>Users/App/Account" title="<?php echo __('Login'); ?>"><?php echo __('Login'); ?></a>
				
		<?php } ?>
		
		</article>
		
		<h1><?php echo __('Admin'); ?> - <?php echo $strings['title']; ?></h1>
			
		<nav></nav>
	</header>
