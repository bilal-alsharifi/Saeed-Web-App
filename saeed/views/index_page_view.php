<?php
echo '<ul>';
foreach ($data['news'] as $n) {
    echo '<li>';
    echo '<h2>'.$n['title'].'</h2>';
    echo '<p>'.$n['description'].'</p>';
    echo '</li>';
}
echo '</ul>';
?>