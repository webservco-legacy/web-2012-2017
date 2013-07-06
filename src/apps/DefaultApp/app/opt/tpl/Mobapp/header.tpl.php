	<header>
		<h1><?php echo $strings['title']; ?></h1>
			
		<nav>
		<?php
		if (!empty($menu['web'])) {
			
			echo "<hr>\n";
			
			echo "<ul>\n";
			
			foreach ($menu['web'] as $menu_item) {
				
				$a_href = !empty($menu_item['href']) ? " href=\"{$menu_item['href']}\"" : NULL;
				
				$a_target = !empty($menu_item['target']) ? " target=\"{$menu_item['target']}\"" : NULL;
				
				echo "<li>\n";
				
				echo "<a{$a_href}{$a_target} title=\"{$menu_item['title']}\">{$menu_item['title']}</a>\n";
				
				if (!empty($menu_item['submenu'])) {
					
					echo "<ul>\n";
					
					foreach ($menu_item['submenu'] as $submenu_item) {
						
						$a_href = !empty($submenu_item['href']) ? " href=\"{$submenu_item['href']}\"" : NULL;
						
						$a_target = !empty($submenu_item['target']) ? " target=\"{$submenu_item['target']}\"" : NULL;
						
						echo "<li>\n";
						
						echo "<a{$a_href}{$a_target} title=\"{$submenu_item['title']}\">{$submenu_item['title']}</a>\n";
						
						echo "</li>\n";
					}
					
					echo "</ul>\n";
				}
				
				echo "</li>\n";
			}
			
			echo "</ul>\n";
			
			echo "<hr>\n";
		}
		?>
		</nav>
	</header>
