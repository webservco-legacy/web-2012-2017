<article>

    <?php
    if (!empty($item)) {
        
        echo "<strong>{$item['item_title']}</strong><br />{$item['item_description']}<br />";
        
    } else {
        
        echo $strings['no_data'];
    }
    ?>
</article>