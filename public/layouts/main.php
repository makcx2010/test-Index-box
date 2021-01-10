<?php
/** @var string $title */
/** @var string $content */
/** @var \system\View $this */
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/public/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="/public/js/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
    <?php ?>
    <title><?php echo $title ?></title>
</head>
<body>
<header class="header">
    <ul>
        <li><a href="#">Test</a></li>
        <li><a href="#">Test</a></li>
        <li><a href="#">Test</a></li>
        <li><a href="#">Test</a></li>
    </ul>
</header>
<?php echo $content ?>
<footer class="footer">
    <p class="sign">Easter egg!</p>
</footer>
</body>
</html>