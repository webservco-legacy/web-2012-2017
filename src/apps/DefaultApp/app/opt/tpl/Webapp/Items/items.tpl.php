<article>
    <?php
    if (!empty($items)) {
        
        foreach ($items as $id => $item) {
            
            echo "{$id}: <a href=\"{$url['www']}Items/App/Item/id/{$id}\"><strong>{$item['item_title']}</strong></a><br />";
        }
        
    } else {
        
        echo $strings['no_data'];
    }
    ?>
</article>