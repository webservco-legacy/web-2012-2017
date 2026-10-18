<article>
    <?php
    if (!empty($items)) {
        
        foreach ($items as $id => $item) {
            
            echo "{$id}: <a href=\"{$url['www']}Users/Admin/User/id/{$id}\"><strong>{$item['user_name_first']} {$item['user_name_last']}</strong> ({$item['user_email']})</a><br />";
        }
        
    } else {
        
        echo $strings['no_data'];
    }
    ?>
</article>