<?php

use system\App;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'env.php';

$app = new App(__DIR__);

$app->db->applyMigrations();