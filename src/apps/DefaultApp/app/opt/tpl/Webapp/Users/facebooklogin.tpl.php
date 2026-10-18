<?php
echo '<article>';

if (!empty($facebook['errors'])) {
    
    echo '<p class="strong ko">';
    foreach ($facebook['errors'] as $key => $value) {
        echo "{$value}<br />";
    }
    echo '</p>';
}

echo '<p class="clearfix">';

echo "<a href=\"{$facebook['login_url']}\" title=\"{$facebook['login_title']}\">{$facebook['login_title']}</a>";

echo '</p>';

echo '</article>';
?>