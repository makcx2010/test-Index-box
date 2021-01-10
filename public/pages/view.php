<?php /** @var array $article */ ?>

<div class="content">
    <?php
    echo '<div id="article">';
    echo '<div class="article-wrap">';
    echo '<div class="title"><h2>' . $article['title'] . '</h2></div>';
    echo '<div class="body"><p>' . $article['body'] . '</p></div>';
    echo '<div class="created-at">' . date('m/d/yy', $article['time_create']) . '</div>';
    echo '</div>';
    echo '</div>';
    ?>
</div>