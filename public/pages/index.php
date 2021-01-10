<?php
/** @var \models\Blog $articles */
/** @var int $countPages */
?>
<div class="sidebar">
    <ul class="filters">
        <h2>Фильтры:</h2>
        <li>
            <label>Количество просмотров: <input id="number-views" type="text"></label>
        </li>
        <li>
            <label>Продукт: <input id="product" type="text"></label>
        </li>
        <li>
            <label>Количество записей на странице:
                <select name="limit" id="limit">
                    <option value="1">1</option>
                    <option value="3">3</option>
                    <option value="5">5</option>
                    <option value="10" selected="selected">10</option>
                    <option value="20">20</option>
                </select>
            </label>
        </li>
        <li>
            <label>Дата добавления: <input id="created-at" type="text" autocomplete="off"></label>
        </li>
    </ul>
</div>
<div class="content content-main">
    <div class="content-child">
        <h2 id="page">Страница: <span>1</span></h2>
        <?php if (!empty($articles)) {
            echo '<div id="articles">';
            foreach ($articles as $article) {
                echo '<a class="article-wrap" href="/main/view/' . $article['href'] . '">';
                echo '<div class="title"><h2>' . $article['title'] . '</h2></div>';
                echo '<div class="description"><p>' . $article['description'] . '</p></div>';
                echo '<div class="views"><span><i class="fa fa-eye"></i> ' . $article['views'] . '</span></div>';
                echo '</a>';
            }
            echo '</div>';
        } else {
            echo '<h2 class="not-found">Записи не найдены.</h2>';
        }
        echo '<ul class="pagination">';
        for ($i = 1; $i <= $countPages; $i++) {
            echo '<li data-value="' . $i . '"><span>' . $i . '</span></li>';
        }
        echo '</ul>';
        ?>
    </div>
</div>
<script src="/public/js/index.js"></script>