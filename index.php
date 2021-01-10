<?php
//declare(strict_types=1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use system\App;
use system\View;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'env.php';

try {
    $app = new App(__DIR__);
    $app->run();
} catch (\Exception $e) {
    View::render('Error', '/public/pages/error', ['message' => $e->getMessage()]);
}
?>
