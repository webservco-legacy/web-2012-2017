<article>
    <p class="content_description">
            <?php echo $app_intro; ?>
        </p>
</article>

<article>
        <?php
        
        if ('tablet' == $webapp['device_type']) {
            echo '<div class="container_apps_tab">';
        }
        
        if (!empty($apps)) {
            
            $c = 1;
            
            foreach ($apps as $app_data) {
                $img_top = !empty($app_data['assets'][0]) ? "http://www.italiamultimedia.com/upload/app_images/{$app_data['assets'][0]}" : NULL;
                $img_bottom = !empty($app_data['assets'][1]) ? "http://www.italiamultimedia.com/upload/app_images/{$app_data['assets'][1]}" : NULL;
                
                ?>
                
            <div class="box_credits<?php if('tablet' == $webapp['device_type']) { echo ' tab_credits'; } ?>">

        <a href="<?php echo $app_data['market_url']; ?>"><img
            src="<?php echo $img_top; ?>" align="left" class="icon" /></a>

        <div class="box_text">

            <a href="<?php echo $app_data['market_url']; ?>"> <strong><?php echo $app_data['app_name']; ?></strong>
            </a> <br /> <a class="<?php echo __("install_button"); ?>"
                href="<?php echo $app_data['market_url']; ?>">&#160;</a>

        </div>

        <br class="clear" />

        <div class="descrizione">
            <small><?php echo $app_data['app_short_description']; ?></small>
        </div>


        <a href="<?php echo $app_data['market_url']; ?>"><img
            src="<?php echo $img_bottom; ?>" class="image" /></a>

    </div>
                    <?php
                if ('tablet' != $webapp['device_type']) {
                    echo '<br class="clear" />';
                }
            }
        }
        
        if ('tablet' == $webapp['device_type']) {
            echo '</div>';
        }
        ?> 
</article>