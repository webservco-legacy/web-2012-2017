<article>
    <?php if (!empty($item_id) && !empty($strings['message'])) { ?>
    
    <p><?php echo $strings['message']; ?></p>
    
    <p>
        <a class="button" href="<?php echo $url['items_admin']; ?>" title="<?php echo __('Items'); ?>"><?php echo __('Items'); ?></a>
    </p>
    
    <p>
        <a class="button" href="<?php echo $url['www']; ?>Items/Admin/Modify/id/<?php echo $item_id; ?><?php echo $url['next_string']; ?>" title="<?php echo __('Item'); ?> <?php echo $item_id; ?>"><?php echo __('Item'); ?> <?php echo $item_id; ?></a>
    </p>
    
    <p>
        <a class="button" href="<?php echo $url['www']; ?>Items/Admin/Add" title="<?php echo __('Add item'); ?>"><?php echo __('Add item'); ?></a>
    </p>
    
    <?php
    } else { 
        
        echo $strings['no_data'];
    }
    ?>
</article>